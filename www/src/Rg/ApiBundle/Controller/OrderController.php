<?php

namespace Rg\ApiBundle\Controller;

use Doctrine\Common\Collections\Collection;
use Rg\ApiBundle\Cart\Cart;
use Rg\ApiBundle\Cart\CartItem;
use Rg\ApiBundle\Cart\CartPatritem;
use Rg\ApiBundle\Entity\Item;
use Rg\ApiBundle\Entity\Legal;
use Rg\ApiBundle\Entity\Order;
use Rg\ApiBundle\Entity\Patritem;
use Rg\ApiBundle\Exception\OrderException;
use Rg\ApiBundle\Exception\PlatronException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OrderController extends Controller
{
    public function createAction(Request $request, SessionInterface $session)
    {
        $doctrine = $this->getDoctrine();

        /** @var Cart $cart */
        $cart = unserialize($session->get('cart'));

        # # #
        $order_details = json_decode(
            $request->getContent()
        );
        // если передан не json
        if (is_null($order_details)) {
            $error = 'not valid json.';
            return (new Out())->json(['error' => $error, 'debug' => $order_details,]);
        }

        $order = new Order();
        $order->setDate(new \DateTime());

        if ($order_details->is_legal) {
            // заказывает ЮЛ
            try {
                $legal = $this->constructLegalFromJson($order_details);
            } catch (OrderException $e) {
                $error = 'Not valid data given.';
                return (new Out())->json(['error' => $error, 'description' => $e->getMessage(),]);
            }

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

            # адрес
            $city = $this->getDoctrine()->getRepository('RgApiBundle:City')
                ->findOneBy(['id' => $order_details->city_id]);
            if (is_null($city)) {
                $error = 'City with id ' . $order_details->city_id . ' not found.';
                return (new Out())->json(['error' => $error]);
            }                                         //                                          x_x
                                                      //                                   x_x
            // не добавляю регион и нас.пункт, они уже приходят с фронта ==> =/>     =>/
            $address_components = [                  //                      /|\   /|\
//                $city->getArea()->getName(),       //                     /  \  /  \
//                $city->getName(),
                $order_details->address,
            ];
            $order->setAddress(join(', ', $address_components));

            #### фио
            $order->setName($order_details->name);

            #### телефон
            $order->setPhone($order_details->phone);

            #### mail
            $email = $order_details->email;
            if (!$this->get('rg_api.legal_validator')->isValidEmail($email)) {
                $error = 'Not valid data given.';
                return (new Out())->json(['error' => $error, 'description' => 'Email ' . $email . ' is not valid.',]);
            }
            $order->setEmail($email);
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
        // запрещаем юрлицу платить квитанцией
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

        ## переход к оплате
        $payment_name = $order->getPayment()->getName();

        if ($payment_name == 'platron') {
            ## инициализировать платёж:
            # передать данные о платеже,
            # получить №транзакции и урл для редиректа
            /** @var \SimpleXMLElement $platron_response */
            try {
                $platron_response = $this->get('rg_api.platron')->init($order);
            } catch (PlatronException $e) {
                // платрон дважды вернул пустую строку, или у нас с заказом что-то не то.

                // аннулировать заказ
                $em->remove($order);
                $em->flush();

                //сообщить об ошибке
                $error = [
                    'error' => 'Platron: error',
                    'description' => $e->getMessage(),
                ];
                return (new Out())->json($error);
            }

            $redirect_url = (string) $platron_response->pg_redirect_url;
            $pg_payment_id = (string) $platron_response->pg_payment_id;

            ## сохранить id транзакции Платрона
            $order->setPgPaymentId($pg_payment_id);
            $em->persist($order);

            ## с платроном всё в порядке, очищаем корзину
            $this->get('rg_api.cart_controller')->emptyAction($session);

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
            ## очищаем корзину
            $this->get('rg_api.cart_controller')->emptyAction($session);

            ## записать в очередь почтовое уведомление
            $em->persist($this->get('rg_api.notification_queue')->onOrderCreate($order));
            $em->flush();

            /*
             * Максим: возвратим на фронт не готовую квитанцию, а ссылку на неё.
             */
//            return $this->createReceipt($order, $items, $patritems);
            $permalink_id = $this->get('rg_api.encryptor')->encryptOrderId($order->getId());
            $url = join('', [
                'https://rg.ru/subsmag',
                $this->generateUrl(
                    'rg_api_get_receipt_by_order',
                    ['enc_id' => $permalink_id]
                ),
            ]);
            $resp = [
                'order_id' => $order->getId(),
                'url' => $url,
            ];
        } elseif ($payment_name == 'invoice') {
            ## очищаем корзину
            $this->get('rg_api.cart_controller')->emptyAction($session);

            ## записать в очередь почтовое уведомление
            $em->persist($this->get('rg_api.notification_queue')->onOrderCreate($order));
            $em->flush();

            /*
             * То же, что с квитанцией. Только ссылку на фронт.
             */
//            return $this->createInvoice($order, $items, $patritems);
            $permalink_id = $this->get('rg_api.encryptor')->encryptOrderId($order->getId());
            $url = join('', [
                'https://rg.ru/subsmag',
                $this->generateUrl(
                    'rg_api_get_invoice_by_order',
                    ['enc_id' => $permalink_id]
                ),
            ]);
            $resp = [
                'order_id' => $order->getId(),
                'url' => $url,
            ];
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
                $cost = ($patriff->getCataloguePrice() + $patriff->getDeliveryPrice());
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
        $url = join('', [
            'https://rg.ru/subsmag',
            $this->generateUrl(
                'rg_api_get_receipt_by_order',
                ['enc_id' => $permalink_id]
            ),
        ]);

        $rendered_response = $this->render('@RgApi/order/receipt.html.twig', [
            'vendor' => $vendor,
            'order' => $order,
            'price' => $price,
            'due_date' => $due_date,
            'goods' => $names_list,
            'permalink_id' => $permalink_id,
            'receipt_url' => $url,
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
        $price->decimal = floor( ( $order_price - $price->integer ) * 100);

        $due_date = $order->getDate()->add(
            new \DateInterval('P7D')
        );

        $ndsless_total = 0;
        $nds_total = 0;

        if ($items instanceof Collection) $items = $items->toArray();
        $invoice_items = array_map(
            function (Item $item) use (&$nds_total, &$ndsless_total) {
                $tariff = $item->getTariff();

                $name = $this->get('rg_api.item_name')->form($item);

                $delivery_cost = $item->getQuantity() * $item->getCost() * $tariff->getDeliveryPrice() / ($tariff->getDeliveryPrice() + $tariff->getCataloguePrice());

                $del_nds_float = round($delivery_cost - ($delivery_cost / 1.18), 2, PHP_ROUND_HALF_DOWN);
                $del_ndsless_float = $delivery_cost - $del_nds_float;

                $del_nds = number_format($del_nds_float, 2, '.', '');
                $del_ndsless = number_format($del_ndsless_float, 2, '.', '');

                $catalogue_cost = $item->getQuantity() * $item->getCost() * $tariff->getCataloguePrice() / ($tariff->getDeliveryPrice() + $tariff->getCataloguePrice());

                $cat_nds_float = round($catalogue_cost - ($catalogue_cost / 1.10), 2, PHP_ROUND_HALF_DOWN);
                $cat_ndsless_float = $catalogue_cost - $cat_nds_float;

                $cat_nds = number_format($cat_nds_float, 2, '.', '');
                $cat_ndsless = number_format($cat_ndsless_float, 2, '.', '');

                $ndsless_total += $cat_ndsless_float + $del_ndsless_float;
                $nds_total += $cat_nds_float + $del_nds_float;

                return [
                    'name' => $name,
                    'quantity' => $item->getQuantity(),
                    'cat_cost' => $catalogue_cost,
                    'cat_nds' => $cat_nds,
                    'cat_ndsless' => $cat_ndsless,
                    'del_cost' => $delivery_cost,
                    'del_nds' => $del_nds,
                    'del_ndsless' => $del_ndsless,
                ];
            },
            $items
        );

        if ($patritems instanceof Collection) $patritems = $patritems->toArray();
        $invoice_patritems = array_map(
            function (Patritem $patritem) use (&$nds_total, &$ndsless_total) {
                $patriff = $patritem->getPatriff();
                $name = "Родина №" . $patriff->getIssue()->getMonth() . "'" . $patriff->getIssue()->getYear();

                $patriff = $patritem->getPatriff();

//                $delivery_cost = $patriff->getDeliveryPrice() * $patritem->getQuantity();
                $delivery_cost = $patritem->getQuantity() * $patritem->getCost() * $patriff->getDeliveryPrice() / ($patriff->getDeliveryPrice() + $patriff->getCataloguePrice());

                $del_nds_float = round($delivery_cost - ($delivery_cost / 1.18), 2, PHP_ROUND_HALF_DOWN);
                $del_ndsless_float = $delivery_cost - $del_nds_float;

                $del_nds = number_format($del_nds_float, 2, '.', '');
                $del_ndsless = number_format($del_ndsless_float, 2, '.', '');

//                $catalogue_cost = $patriff->getCataloguePrice() * $patritem->getQuantity();
                $catalogue_cost = $patritem->getQuantity() * $patritem->getCost() * $patriff->getCataloguePrice() / ($patriff->getDeliveryPrice() + $patriff->getCataloguePrice());

                $cat_nds_float = round($catalogue_cost - ($catalogue_cost / 1.10), 2, PHP_ROUND_HALF_DOWN);
                $cat_ndsless_float = $catalogue_cost - $cat_nds_float;

                $cat_nds = number_format($cat_nds_float, 2, '.', '');
                $cat_ndsless = number_format($cat_ndsless_float, 2, '.', '');

                $ndsless_total += $cat_ndsless_float + $del_ndsless_float;
                $nds_total += $cat_nds_float + $del_nds_float;

                return [
                    'name' => $name,
                    'quantity' => $patritem->getQuantity(),
                    'cat_cost' => $patriff->getCataloguePrice() * $patritem->getQuantity(),
                    'cat_nds' => $cat_nds,
                    'cat_ndsless' => $cat_ndsless,
                    'del_cost' => $delivery_cost,
                    'del_nds' => $del_nds,
                    'del_ndsless' => $del_ndsless,
                ];
            },
            $patritems
        );

        $nds = number_format($nds_total, 2, '.', '');
        $ndsless = number_format($ndsless_total, 2, '.', '');

        $permalink_id = $this->get('rg_api.encryptor')->encryptOrderId($order->getId());
        $url = join('', [
            'https://rg.ru/subsmag',
            $this->generateUrl(
                'rg_api_get_invoice_by_order',
                ['enc_id' => $permalink_id]
            ),
        ]);

        $rendered_response = $this->render('@RgApi/order/invoice.html.twig', [
            'vendor' => $vendor,
            'order' => $order,
            'legal' => $order->getLegal(),
            'price' => $price,
            'nds' => $nds,
            'ndsless' => $ndsless,
            'text_price' => $this->get('rg_api.price_to_text_converter')->convert($price->integer, $price->decimal),
            'due_date' => $due_date,
            'items' => $invoice_items,
            'patritems' => $invoice_patritems,
            'permalink_id' => $permalink_id,
            'invoice_url' => $url,
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

    private function constructLegalFromJson(\stdClass $order_details): Legal
    {
        $legal = new Legal();

        $this->get('rg_api.legal_validator')->validateLegal($order_details);

        ############# валидируются
        $legal->setName($order_details->org_name);
        $legal->setInn($order_details->inn);
        $legal->setKpp($order_details->kpp);
        $legal->setPostcode($order_details->postcode);
        $legal->setContactPhone($order_details->contact_phone);

        $contactEmail = $order_details->contact_email;
        $legal->setContactEmail($contactEmail);

        $legal->setDeliveryPostcode($order_details->delivery_postcode);
        #############

        //не валидируются
        $legal->setBankName($order_details->bank_name ?? '');
        $legal->setBankAccount($order_details->bank_account ?? '');
        $legal->setBankCorrAccount($order_details->bank_corr ?? '');
        $legal->setBik($order_details->bik ?? '');

        $legal->setCity($order_details->city ?? '');
        $legal->setStreet($order_details->street ?? '');
        $legal->setBuildingNumber($order_details->building_number ?? '');
        $legal->setBuildingSubnumber($order_details->building_subnumber ?? '');
        $legal->setBuildingPart($order_details->building_part ?? '');
        $legal->setAppartment($order_details->appartment ?? '');

        $legal->setContactName($order_details->contact_name ?? '');
        $legal->setContactFax($order_details->contact_fax ?? '');

        $delivery_city = $this->getDoctrine()->getRepository('RgApiBundle:City')
            ->findOneBy(['id' => $order_details->delivery_city_id]);
        if (is_null($delivery_city))
            throw new OrderException('City with id ' . $order_details->delivery_city_id . ' not found.');

        $legal->setDeliveryCity($delivery_city);

        $legal->setDeliveryStreet($order_details->delivery_street ?? '');
        $legal->setDeliveryBuildingNumber($order_details->delivery_building_number ?? '');
        $legal->setDeliveryBuildingSubnumber($order_details->delivery_building_subnumber ?? '');
        $legal->setDeliveryBuildingPart($order_details->delivery_building_part ?? '');
        $legal->setDeliveryAppartment($order_details->delivery_appartment ?? '');

        return $legal;
    }
}

