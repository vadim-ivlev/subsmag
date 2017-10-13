<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Cart\Cart;
use Rg\ApiBundle\Cart\CartItem;
use Rg\ApiBundle\Cart\CartPatritem;
use Rg\ApiBundle\Entity\City;
use Rg\ApiBundle\Entity\Item;
use Rg\ApiBundle\Entity\Legal;
use Rg\ApiBundle\Entity\Notification;
use Rg\ApiBundle\Entity\Order;
use Rg\ApiBundle\Entity\Patritem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderController extends Controller
{
    const MONTH = 2048; //100000'000000

    public function createAction(Request $request, SessionInterface $session)
    {
        $doctrine = $this->getDoctrine();

        /** @var Cart $cart */
        $cart = unserialize($session->get('cart'));

        # # #
        $order_details = json_decode(
            $request->getContent()
        );

        $order = new Order();
        $order->setDate(new \DateTime());

        if ($order_details->is_legal) {
            // заказывает ЮЛ
            $legal = new Legal();

            $legal->setName($order_details->org_name);
            $legal->setInn($order_details->inn);
            $legal->setKpp($order_details->kpp);
            $legal->setBankName($order_details->bank_name);
            $legal->setBankAccount($order_details->bank_account);
            $legal->setBankCorrAccount($order_details->bank_corr);
            $legal->setBik($order_details->bik);

            $city = $doctrine->getRepository('RgApiBundle:City')
                ->findOneBy(['id' => $order_details->city_id]);
            $legal->setCity($city);

            $legal->setPostcode($order_details->postcode);
            $legal->setStreet($order_details->street);
            $legal->setBuildingNumber($order_details->building_number);
            $legal->setBuildingSubnumber($order_details->building_subnumber);
            $legal->setBuildingPart($order_details->building_part);
            $legal->setAppartment($order_details->appartment);

            $legal->setContactName($order_details->contact_name);
            $legal->setContactPhone($order_details->contact_phone);
            $legal->setContactFax($order_details->contact_fax ?? '');
            $legal->setContactEmail($order_details->contact_email);

            $delivery_city = $doctrine->getRepository('RgApiBundle:City')
                ->findOneBy(['id' => $order_details->delivery_city_id]);
            $legal->setDeliveryCity($delivery_city);

            $legal->setDeliveryPostcode($order_details->delivery_postcode);
            $legal->setDeliveryStreet($order_details->delivery_street);
            $legal->setDeliveryBuildingNumber($order_details->delivery_building_number);
            $legal->setDeliveryBuildingSubnumber($order_details->delivery_building_subnumber);
            $legal->setDeliveryBuildingPart($order_details->delivery_building_part);
            $legal->setDeliveryAppartment($order_details->delivery_appartment);

            $em = $doctrine->getManager();
            $em->persist($legal);
            $em->flush();
            $order->setLegal($legal);

            ###
            $order->setAddress($this->deliveryAddressToString($legal));
            #### фио
            $order->setName($order_details->contact_name);
            #### телефон
            $order->setPhone($order_details->contact_phone);
            #### mail
            $order->setEmail($order_details->contact_email);
        } else {
            // заказывает ФЛ
            ### обработать контактные данные

            $order->setAddress($order_details->address);
            #### фио
            $order->setName($order_details->name);
            #### телефон
            $order->setPhone($order_details->phone);
            #### mail
            $order->setEmail($order_details->email);
        }

        $order->setIsPaid(false);
        $order->setPgPaymentId('');

        ### способ оплаты
        $payment = $doctrine
            ->getRepository('RgApiBundle:Payment')
            ->findOneBy(['name' => $order_details->payment]);
        if (!$payment) {
            $resp = [
                'error' => 'Payment not found',
            ];
            return (new Out())->json($resp);
        }
        if (!is_null($order->getLegal()) && $payment->getName() == 'receipt') {
            $resp = [
                'error' => 'Payment type not available for legal entity.',
            ];
            return (new Out())->json($resp);
        }
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
            if ($platron_response === null) {
                $error = [
                    'error' => 'Platron: unparseable response',
                    'description' => 'Order has been created, but an unparseable response received from Platron.',
                ];
                return (new Out())->json($error);
            }
            $redirect_url = (string) $platron_response->pg_redirect_url;
            $pg_payment_id = (string) $platron_response->pg_payment_id;

            ## сохранить id транзакции Платрона
            $order->setPgPaymentId($pg_payment_id);
            $em->persist($order);

            ## подготовить запись для почтового уведомления
            $em->persist($this->get('rg_api.notification_queue')->onOrderCreate($order));

            $em->flush();

                # отправить пользователя на платрон (на фронте)
            $resp = [
                'order' => $order->getId(),
                'total' => $order->getTotal(),
                'pg_payment_id' => $pg_payment_id,
                'pg_redirect_url' => $redirect_url,
            ];

        } elseif ($payment_name == 'receipt') {

            ## записать в очередь почтовое уведомление
            $em->persist($this->get('rg_api.notification_queue')->onOrderCreate($order));
            $em->flush();

            return $this->createReceipt($order, $items, $patritems);
        } elseif ($payment_name == 'invoice') {
            ## записать в очередь почтовое уведомление
            $em->persist($this->get('rg_api.notification_queue')->onOrderCreate($order));
            $em->flush();
            return $this->createInvoice($order, $items, $patritems);
        } else {
            $resp = [
                'error' => 'Wrong payment type received.'
            ];
        }

        return (new Out())->json($resp);

    }

    public function getInvoiceByOrderIdAction($enc_id)
    {
        $id = $this->get('rg_api.encryptor')->decryptOrderId($enc_id);
        $doctrine = $this->getDoctrine();

        $order = $doctrine->getRepository('RgApiBundle:Order')
            ->findOneBy(['id' => $id]);

        if (is_null($order)) {
            return new Response('There is no such an order.');
        }

        $items = $order->getItems();
        $patritems = $order->getPatritems();

        return $this->createInvoice($order, $items, $patritems);
    }

    public function getReceiptByOrderIdAction($enc_id)
    {
        $id = $this->get('rg_api.encryptor')->decryptOrderId($enc_id);
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

        $permalink_id = $this->get('rg_api.encryptor')->encryptOrderId($order->getId());

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

    private function createInvoice(Order $order, $items, $patritems)
    {
         $doctrine = $this->getDoctrine();

        # подготовить платёжное поручение
        $vendor = $doctrine->getRepository('RgApiBundle:Vendor')
            ->findOneBy(['keyword' => 'zaorg']); // magic word. What if there'd be more than one vendor?

        $order_price = $order->getTotal();

        $price = new \stdClass();
        $price->total = number_format($order_price, 2, ',', '');
        $price->integer = floor($order_price);
//            $price->decimal = fmod($price->total, 1); // bad idea cause of float division tricks!!!
        $price->decimal = floor( ( $order_price - $price->integer ) * 100);

        $price_for_nds = number_format($order_price, 2, '.', '');

        $nds_float = $order_price - ($order_price / 1.18);
        $ndsless_price_float = $price_for_nds - $nds_float;

        $nds = number_format($nds_float, 2, '.', '');
        $ndsless_price = number_format($ndsless_price_float, 2, '.', '');
//        $nds = bcsub($price_for_nds, bcdiv($price_for_nds, '1.18', 2), 2);
//        $ndsless_price = bcsub($price_for_nds, $nds, 2);

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

//        $names_list = join(', ', $goods);

        $permalink_id = $this->get('rg_api.encryptor')->encryptOrderId($order->getId());

        $rendered_response = $this->render('@RgApi/order/invoice.html.twig', [
            'vendor' => $vendor,
            'order' => $order,
            'legal' => $order->getLegal(),
            'price' => $price,
            'nds' => $nds,
            'ndsless_price' => $ndsless_price,
            'text_price' => $this->get('rg_api.price_to_text_converter')->convert($price->integer, $price->decimal),
            'due_date' => $due_date,
//            'goods' => $names_list,
            'goods' => $goods,
            'permalink_id' => $permalink_id,
        ]);

        return $rendered_response;
    }

    private function deliveryAddressToString(Legal $legal)
    {
        $del = [
            $legal->getDeliveryPostcode(),
            $legal->getDeliveryCity()->getArea()->getName(),
            $legal->getDeliveryCity()->getName(),
            $legal->getDeliveryStreet(),
            $legal->getDeliveryBuildingNumber(),
            $legal->getDeliveryBuildingSubnumber(),
            $legal->getDeliveryBuildingPart(),
            $legal->getDeliveryAppartment(),
        ];

        return join(' ', $del);
    }
}
