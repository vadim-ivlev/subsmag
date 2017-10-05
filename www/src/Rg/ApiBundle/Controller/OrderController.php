<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Cart\Cart;
use Rg\ApiBundle\Cart\CartItem;
use Rg\ApiBundle\Cart\CartPatritem;
use Rg\ApiBundle\Entity\Item;
use Rg\ApiBundle\Entity\Notification;
use Rg\ApiBundle\Entity\Order;
use Rg\ApiBundle\Entity\Patritem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class OrderController extends Controller
{
    const MONTH = 2048; //100000'000000
    const CIPHER = 'AES-128-CBC';
    const IV = 'ABC^DEF_GHI+JKL-';
    const PASS = 'try_to_decrypt_it';

    public function createAction(Request $request, SessionInterface $session)
    {
        /** @var Cart $cart */
        $cart = unserialize($session->get('cart'));

        # # #
        $order_details = json_decode(
            $request->getContent()
        );

        $order = new Order();
        $order->setDate(new \DateTime());

        ### обработать контактные данные
        $order->setAddress($order_details->address);
        #### фио
        $order->setName($order_details->name);
        #### телефон
        $order->setPhone($order_details->phone);
        #### mail
        $order->setEmail($order_details->email);

        $order->setIsPaid(false);
        $order->setPgPaymentId('');

        ### способ оплаты
        $doctrine = $this->getDoctrine();
        $payment = $doctrine
            ->getRepository('RgApiBundle:Payment')
            ->findOneBy(['name' => $order_details->payment]);
        $order->setPayment($payment);

        ### подготовим массив подписных позиций
        $items = $this->mapSubscribeItems($cart->getCartItems(), $order);

        ### подготовим массив архивных позиций
        $patritems = $this->mapArchiveItems($cart->getCartPatritems(), $order);

        ### подсчёт Итого
        $total = $this->countTotal($items, $patritems);
        $order->setTotal($total);

        ### записываем заказ в БД
        $em = $doctrine->getManager();
        $em->persist($order);
        foreach ($items as $item) {
            $em->persist($item);
        }
        foreach ($patritems as $patritem) {
            $em->persist($patritem);
        }
        $em->flush();

        ## очищаем корзину
        $this->get('rg_api.cart_controller')->emptyAction($session);

        ## переход к оплате
        $payment_name = $order->getPayment()->getName();

        if ($payment_name == 'platron') {
            ## инициализировать платёж:
                # передать данные о платеже,
                # получить №транзакции и урл для редиректа
            /** @var \SimpleXMLElement $platron_response */
            $platron_response = $this->get('rg_api.platron')->init($order);
            $redirect_url = (string) $platron_response->pg_redirect_url;
            $pg_payment_id = (string) $platron_response->pg_payment_id;

            ## сохранить id транзакции Платрона
            $order->setPgPaymentId($pg_payment_id);
            $em->persist($order);

            ## подготовить запись для почтового уведомления
            $em->persist($this->createNotification($order));

            $em->flush();

                # отправить пользователя на платрон (на фронте)
            $resp = [
                'order' => $order->getId(),
                'total' => $order->getTotal(),
                'pg_payment_id' => $pg_payment_id,
                'pg_redirect_url' => $redirect_url,
            ];

        } elseif ($payment_name == 'receipt') {
            return $this->createReceipt($order, $items, $patritems);
        } else {
            $resp = [
                'error' => 'bad bad bad',
                'description' => 'Wrong payment type.'
            ];
        }

        return (new Out())->json($resp);

    }

    public function getReceiptByOrderIdAction($enc_id)
    {
        $id = $this->decryptOrderId($enc_id);
        $doctrine = $this->getDoctrine();

        $order = $doctrine->getRepository('RgApiBundle:Order')
            ->findOneBy(['id' => $id]);

        if (is_null($order)) {
            return new Response('There is no such an order.');
        }

        $items = $order->getItems();
        $patritems = $order->getPatritems();

        return $this->createReceipt($order, $items, $patritems);
    }

    private function countTotal(array $items, array $patritems)
    {
        //
        $items_subtotal = array_reduce(
            $items,
            function ($total = 0, Item $item) {
                $item_total = $item->getCost() * $item->getQuantity();
                $total += $item_total;

                return $total;
            }
        );

        $patritems_subtotal = array_reduce(
            $patritems,
            function ($total = 0, Patritem $patritem) {
                $item_total = $patritem->getCost() * $patritem->getQuantity();
                $total += $item_total;

                return $total;
            }
        );

        $total = $items_subtotal + $patritems_subtotal;

        return $total;
    }

    private function mapSubscribeItems(array $cart_items, Order $order)
    {
        $doctrine = $this->getDoctrine();
        $items = array_map(
            function (CartItem $cart_item) use ($doctrine, $order) {
                $item = new Item();

                $item->setQuantity($cart_item->getQuantity());

                $tariff = $doctrine
                    ->getRepository('RgApiBundle:Tariff')
                    ->findOneBy(
                        [
                            'id' => $cart_item->getTariff()
                        ]);
                $item->setTariff($tariff);

                $calculator = $this->get('rg_api.product_cost_calculator');
                $timeunit_amount = $calculator->calculateTimeunitAmount($tariff, $cart_item->getDuration());
                $item->setTimeunitAmount($timeunit_amount);

                $cost = $calculator->calculateItemCost($tariff, $cart_item->getDuration());
                $item->setCost($cost);

                $month = $doctrine
                    ->getRepository('RgApiBundle:Month')
                    ->findOneBy([
                        'number' => $cart_item->getFirstMonth(),
                        'year' => $cart_item->getYear(),
                    ]);
                $item->setMonth($month);

                $item->setDuration($cart_item->getDuration());

                $item->setOrder($order);

                return $item;
            },
            $cart_items
        );

        return $items;
    }

    private function mapArchiveItems(array $cart_patritems, Order $order)
    {
        $doctrine = $this->getDoctrine();
        return array_map(
            function (CartPatritem $cart_patritem) use ($doctrine, $order) {
                $patritem = new Patritem();

                $patritem->setQuantity($cart_patritem->getQuantity());

                $patriff = $doctrine
                    ->getRepository('RgApiBundle:Patriff')
                    ->findOneBy(['id' => $cart_patritem->getPatriff()]);
                $patritem->setPatriff($patriff);

                // стоимость единицы архивной позиции
                $cost = $patriff->getPrice();
                $patritem->setCost($cost);

                // к какому заказу относится
                $patritem->setOrder($order);

                return $patritem;
            },
            $cart_patritems
        );
    }

    private function createReceipt(Order $order, $items, $patritems)
    {
        $doctrine = $this->getDoctrine();

        # подготовить банковскую квитанцию
        $vendor = $doctrine->getRepository('RgApiBundle:Vendor')
            ->findOneBy(['keyword' => 'zaorg']); // magic word. What if there'd be more than one vendor?

        $price = new \stdClass();
        $price->total = $order->getTotal();
        $price->integer = floor($price->total);
//            $price->decimal = fmod($price->total, 1); // bad idea cause of float division tricks!!!
        $price->decimal = floor( ( $price->total - $price->integer ) * 100);

        $due_date = $order->getDate()->add(
            new \DateInterval('P7D')
        );

        ## the names of ordered items
        $goods = [];

        /** @var Item $item */
        foreach ($items as $item) {
            $product = $item->getTariff()->getProduct();

            $month = $item->getMonth();
            $first_month = $month->getNumber();
            $last_month = $first_month + $item->getDuration();

            $short_descr = $product->getName() .
                ' ' .
                $first_month .
                '-' .
                $last_month .
                "'" .
                $month->getYear();

            $goods[] = $short_descr;
        }

        /** @var Patritem $patritem */
        foreach ($patritems as $patritem) {
            $issue = $patritem->getPatriff()->getIssue();

            $patria = 'Родина №' .
                $issue->getMonth() .
                "'" .
                $issue->getYear();

            $goods[] = $patria;
        }

        $names_list = join(', ', $goods);

        $permalink_id = $this->encryptOrderId($order->getId());

        ## записать в очередь почтовое уведомление
        $em = $doctrine->getManager();
        $em->persist($this->createNotification($order));
        $em->flush();

        $rendered_response = $this->render('@RgApi/order/receipt.html.twig', [
            'vendor' => $vendor,
            'order' => $order,
            'price' => $price,
            'due_date' => $due_date,
            'goods' => $names_list,
            'permalink_id' => $permalink_id,
        ]);

        return $rendered_response;
    }

    private function encryptOrderId(int $id)
    {
        return base64_encode(openssl_encrypt($id, self::CIPHER, self::PASS, 0, self::IV));
    }

    private function decryptOrderId(string $key)
    {
        return openssl_decrypt(base64_decode($key), self::CIPHER, self::PASS, 0, self::IV);
    }

    private function createNotification(Order $order)
    {
        $notification = new Notification();
        $notification->setType('order_created');
        $notification->setState('queued');
        $notification->setDate(new \DateTime());
        $notification->setOrder($order);
        $notification->setError('');

        return $notification;
    }
}
