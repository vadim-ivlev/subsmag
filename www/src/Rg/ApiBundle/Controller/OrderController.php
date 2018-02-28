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
use Rg\ApiBundle\Entity\Promo;
use Rg\ApiBundle\Exception\OrderException;
use Rg\ApiBundle\Exception\PlatronException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderController extends Controller
{
    /**
     * @var null
     */
    private $pin = null;
    /**
     * @var bool
     */
    private $is_promoted = false;

    /**
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
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

            $order->setPostcode($order_details->delivery_postcode);
            $order->setCity($legal->getDeliveryCity());
        } else {
            // заказывает ФЛ
            ### обработать контактные данные

            # адрес
            $city_id = $order_details->city_id;
            $city = $this->getDoctrine()->getRepository('RgApiBundle:City')
                ->findOneBy(['id' => $city_id]);
            if (is_null($city)) {
                $error = 'City with id ' . $order_details->city_id . ' not found.';
                return (new Out())->json(['error' => $error, 'description' => 'City with id ' . $city_id . ' not found',]);
            }

            $order->setPostcode($order_details->postcode);
            $order->setCity($city);
            $order->setStreet($order_details->street);
            $order->setBuildingNumber($order_details->building_number);
            $order->setBuildingSubnumber($order_details->building_subnumber);
            $order->setBuildingPart($order_details->building_part);
            $order->setAppartment($order_details->appartment);

            $address_components = [
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
        $order->setComment($order_details->comment ?? '');

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

        ### проверим наличие и живость промокода
        $promo = $this->get('rg_api.promo_fetcher')->getPromoOrNull($request, $session);

        ### подготовим массив подписных позиций
        $items = $this->mapSubscribeItems($cart->getCartItems(), $order, $promo);

        ### подготовим массив архивных позиций
        $patritems = $this->mapArchiveItems($cart->getCartPatritems(), $order);


        ### подсчёт Итого
        $total = $this->countTotal($items, $patritems);
        $order->setTotal($total);

        ### записываем заказ в БД
        $em = $doctrine->getManager();
        $em->persist($order);
        #### если был промо и он с пином
        $order->setIsPromoted($this->is_promoted);
        if (!is_null($this->pin)) {
            $this->pin->setOrder($order);
            $em->persist($this->pin);
            $order->setPin($this->pin);
            $em->persist($order);
        }
        foreach ($items as $item) {
            $em->persist($item);
        }
        foreach ($patritems as $patritem) {
            $em->persist($patritem);
        }
        $em->flush();

        ## переход к оплате
        $payment_name = $order->getPayment()->getName();

        switch ($payment_name) {
            case 'platron':
                ## инициализировать платёж:
                # передать данные о платеже,
                # получить №транзакции и урл для редиректа
                /** @var \SimpleXMLElement $platron_init_xml */
                try {
                    $platron_init_xml = $this->get('rg_api.platron')->init($order);
                } catch (PlatronException $e) {
                    // платрон дважды вернул пустую строку, или у нас с заказом что-то не то.

                    // освободить pin
                    $saved_pin = $order->getPin();
                    if (!is_null($saved_pin)) {
                        $saved_pin->setOrder(null);
                        $em->persist($saved_pin);
                        $em->flush();
                    }

                    //сообщить об ошибке
                    $error = [
                        'error' => 'Platron: ошибка инициализации платежа',
                        'description' => $e->getMessage(),
                    ];
                    return (new Out())->json($error);
                }

                $redirect_url = (string) $platron_init_xml->pg_redirect_url;
                $pg_payment_id = (string) $platron_init_xml->pg_payment_id;

                try {
                    /** @var \SimpleXMLElement $platron_receipt_create_xml */
                    $platron_receipt_create_xml = $this->get('rg_api.platron')
                        ->createReceipt($pg_payment_id, $order, $items, $patritems);
                } catch (PlatronException $e) {
                    // платрон не вернул чек

                    // освободить pin
                    $saved_pin = $order->getPin();
                    if (!is_null($saved_pin)) {
                        $saved_pin->setOrder(null);
                        $em->persist($saved_pin);
                        $em->flush();
                    }

                    //сообщить об ошибке
                    $error = [
                        'error' => 'Platron: ошибка создания чека',
                        'description' => $e->getMessage(),
                    ];
                    return (new Out())->json($error);
                }

                ## сохранить id транзакции Платрона
                # и остальные переговоры
                $order->setPgPaymentId($pg_payment_id);
                $order->setPlatronInitXml($platron_init_xml->asXML());
                $order->setPlatronReceiptCreateXml($platron_receipt_create_xml->asXML());
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
                break;
            case 'receipt':
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
                    $this->getParameter('domain'),
                    $this->generateUrl(
                        'rg_api_get_receipt_by_order',
                        ['enc_id' => $permalink_id]
                    ),
                ]);
                $resp = [
                    'order_id' => $order->getId(),
                    'url' => $url,
                ];
                break;
            case 'invoice':
                ## очищаем корзину
                $this->get('rg_api.cart_controller')->emptyAction($session);

                ## записать в очередь почтовое уведомление
                $em->persist($this->get('rg_api.notification_queue')->onOrderCreate($order));
                $em->flush();

                /*
                 * То же, что с квитанцией. Только ссылку на фронт.
                 */
//                return $this->createInvoice($order, $items, $patritems);
                $permalink_id = $this->get('rg_api.encryptor')->encryptOrderId($order->getId());
                $url = join('', [
                    $this->getParameter('domain'),
                    $this->generateUrl(
                        'rg_api_get_invoice_by_order',
                        ['enc_id' => $permalink_id]
                    ),
                ]);
                $resp = [
                    'order_id' => $order->getId(),
                    'url' => $url,
                ];
                break;
            default:
                $resp = [
                    'error' => 'Wrong payment type received.'
                ];
        }

        return (new Out())->json($resp);

    }

    /**
     * @param $enc_id
     * @return Response
     */
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

    /**
     * @param $enc_id
     * @return Response
     */
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

    public function getPlatronReceiptByOrderIdAction($enc_id)
    {
        $id = $this->get('rg_api.encryptor')->decryptOrderId($enc_id);
        $doctrine = $this->getDoctrine();

        $order = $doctrine->getRepository('RgApiBundle:Order')
            ->findOneBy(['id' => $id]);

        if (is_null($order)) {
            return new Response('Нет заказа с таким номером.');
        }

        $vendor = $doctrine->getRepository('RgApiBundle:Vendor')
            ->findOneBy(['keyword' => 'zaorg']); // magic word. What if there'd be more than one vendor?

        // подготовить товарную составляющую
        $items = $order->getItems();
        $patritems = $order->getPatritems();
        $goods = [];

        /** @var Item $item */
        foreach ($items as $item) {
            $item_name = mb_substr($this->get('rg_api.item_name')->form($item), 0, 124);
            ## для каталожной цены
            $discountedCatCost = $item->getDiscountedCatCost();
            if ($discountedCatCost > 0) {
                $goods[] = [
                    'name' => $item_name . ' кат',
                    'price' => $discountedCatCost,
                    'quantity' => $item->getQuantity(),
                    'vat' => 10,
                ];
            }

            ## для доставочной
            $discountedDelCost = $item->getDiscountedDelCost();
            if ($discountedDelCost > 0) {
                $goods[] = [
                    'name' => $item_name . ' дост',
                    'price' => $discountedDelCost,
                    'quantity' => $item->getQuantity(),
                    'vat' => 18,
                ];
            };
        }
        /** @var Patritem $patritem */
        foreach ($patritems as $patritem) {
            $patriff = $patritem->getPatriff();
            $name = "Родина №" . $patriff->getIssue()->getMonth() . "-" . $patriff->getIssue()->getYear();
            ## для каталожной цены
            $pi_discountedCatCost = $patritem->getDiscountedCatCost();
            if ($pi_discountedCatCost > 0) {
                $goods[] = [
                    'name' => $name . ' кат',
                    'price' => $pi_discountedCatCost,
                    'quantity' => $patritem->getQuantity(),
                    'vat' => 10,
                ];
            }

            ## для доставочной
            $pi_discountedDelCost = $patritem->getDiscountedDelCost();
            if ($pi_discountedDelCost > 0) {
                $goods[] = [
                    'name' => $name . ' дост',
                    'price' => $pi_discountedDelCost,
                    'quantity' => $patritem->getQuantity(),
                    'vat' => 18,
                ];
            }
        }

        $goods = array_map(
            function (array $g) {
                $g['cost'] = $g['price'] * $g['quantity'];
                return $g;
            },
            $goods
        );

        // подготовить чековую составляющую
        try {
            $xml = new \SimpleXMLElement($order->getPlatronReceiptCreateXml());
        } catch (\Exception $e) {
            return new Response('В заказе нет данных о чеке.');
        }

        $pg_receipt_id_xml = $xml->xpath('pg_receipt_id');

        if (empty($pg_receipt_id_xml)) {
            return new Response('ОФД-чек ещё не создан. Подождите, когда завершится банковская транзакция.');
        }

//            $pg_receipt_id = (string) $xml->pg_receipt_id;

        try {
            /** @var \SimpleXMLElement $platron_receipt_state_xml */
            $platron_receipt_state_xml = $this
                ->get('rg_api.platron')
                ->getReceiptState($order)
            ;
        } catch (PlatronException $e) {
            // платрон вернул ... что? Смотри лог

            //сообщить об ошибке
            return new Response('Сбой связи с оператором платёжной системы. Повторите позже, пожалуйста.');
        }

//pg_status
//pg_receipt_status
//pg_fiscal_receipt_number
//pg_shift_number
//pg_receipt_date
//pg_fn_number
//pg_ecr_registration_number
//pg_fiscal_document_number
//pg_fiscal_document_attribute

        $pg = [
            'status' => (string) $platron_receipt_state_xml->pg_status,
            'receipt_status' => (string) $platron_receipt_state_xml->pg_receipt_status,
            'fiscal_receipt_number' => (string) $platron_receipt_state_xml->pg_fiscal_receipt_number,
            'shift_number' => (string) $platron_receipt_state_xml->pg_shift_number,
            'receipt_date' => (string) $platron_receipt_state_xml->pg_receipt_date,
            'fn_number' => (string) $platron_receipt_state_xml->pg_fn_number,
            'ecr_registration_number' => (string) $platron_receipt_state_xml->pg_ecr_registration_number,
            'fiscal_document_number' => (string) $platron_receipt_state_xml->pg_fiscal_document_number,
            'fiscal_document_attribute' => (string) $platron_receipt_state_xml->pg_fiscal_document_attribute,
        ];

        $rendered_response = $this->render('@RgApi/order/ofd.html.twig', [
            'vendor' => $vendor,
            'order' => $order,
            'total' => number_format($order->getTotal(), 2, ',', ''),
            'goods' => $goods,
            'pg' => $pg,
        ]);

        return $rendered_response;
    }

    /**
     * @param array $items
     * @param array $patritems
     * @return mixed
     */
    private function countTotal(array $items, array $patritems)
    {
        $items_subtotal = array_reduce(
            $items,
            function ($total = 0, Item $item) {
                $total += $item->getTotal();

                return $total;
            }
        );

        $patritems_subtotal = array_reduce(
            $patritems,
            function ($total = 0, Patritem $patritem) {
                $total += $patritem->getTotal();

                return $total;
            }
        );

        $total = $items_subtotal + $patritems_subtotal;

        return $total;
    }

    /**
     * Магия превращения Золушки в тыкву происходит в этом методе.
     * Подписные позиции из корзины отображаются в заказные позиции.
     * Если человек увидел скидку и она не успела протухнуть, здесь она применится.
     * @param array $cart_items
     * @param Order $order
     * @param Promo|null $promo
     * @return array
     */
    private function mapSubscribeItems(array $cart_items, Order $order, Promo $promo = null)
    {
        $doctrine = $this->getDoctrine();
        $items = array_map(
            function (CartItem $cart_item) use ($doctrine, $order, $promo) {
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
                $duration = $cart_item->getDuration();
                $timeunit_amount = $calculator->calculateTimeunitAmount($tariff, $duration);
                $item->setTimeunitAmount($timeunit_amount);

                $cost = $calculator->calculateItemCost($tariff, $duration);
                $item->setCost($cost);
                $del_cost = $calculator->calculateItemDelCost($tariff, $duration);
                $item->setDelCost($del_cost);
                $cat_cost = $calculator->calculateItemCatCost($tariff, $duration);
                $item->setCatCost($cat_cost);

                ## работаем со скидкой
                ### Скидка ВЫЧИТАЕТСЯ из каталожной цены. Доставочную не трогаем.
                #### Раньше умножали.
                $discount = 0;
                if (!is_null($promo)) {
                    if ($this->get('rg_api.promo_fetcher')->doesPromoFitTariff($promo, $tariff)) {
                        $item->setPromo($promo);
                        $discount = $promo->getDiscount();
                        $this->is_promoted = true;

                        if (
                            is_null($this->pin)
                            && !is_null($promo->pin)
                        ) {
                            $this->pin = $promo->pin;
                        }
                    }
                }
                ##

                $item->setDiscount($discount);

                $disc_del_cost = $del_cost;
                $item->setDiscountedDelCost($disc_del_cost);

                $disc_cat_cost = $calculator->calculateItemCatCost($tariff, $duration, $discount);
                $item->setDiscountedCatCost($disc_cat_cost);

                $total = ($disc_del_cost + $disc_cat_cost) * $item->getQuantity();
                $item->setTotal($total);

                $month = $doctrine
                    ->getRepository('RgApiBundle:Month')
                    ->findOneBy([
                        'number' => $cart_item->getFirstMonth(),
                        'year' => $cart_item->getYear(),
                    ]);
                $item->setMonth($month);

                $item->setDuration($duration);

                $item->setOrder($order);

                return $item;
            },
            $cart_items
        );

        return $items;
    }

    /**
     * @param array $cart_patritems
     * @param Order $order
     * @return array
     */
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

                ## работаем со скидкой
                ### Ждёт своей очереди скидка для архивов.
                $discount = 0;

                $catCost = $patriff->getCataloguePrice();
                $patritem->setCatCost($catCost);

                $delCost = $patriff->getDeliveryPrice();
                $patritem->setDelCost($delCost);

                $discountedDelCost = $delCost;
                $patritem->setDiscountedDelCost($discountedDelCost);

                $discountedCatCost = $catCost - $discount;
                $patritem->setDiscountedCatCost($discountedCatCost);

                $total = ($discountedCatCost + $discountedDelCost) * $patritem->getQuantity();
                $patritem->setTotal($total);

                // к какому заказу относится
                $patritem->setOrder($order);

                return $patritem;
            },
            $cart_patritems
        );
    }

    /**
     * @param Order $order
     * @param $items
     * @param $patritems
     * @return Response
     */
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
            $last_month = $first_month + $item->getDuration() - 1;

            $short_descr = $product->getName() .
                ' ' .
                $first_month .
                '-' .
                $last_month .
                "'" .
                $month->getYear() .
                ' ' .
                $item->getQuantity() .
                ' экз'
            ;

            $goods[] = $short_descr;
        }

        /** @var Patritem $patritem */
        foreach ($patritems as $patritem) {
            $issue = $patritem->getPatriff()->getIssue();

            $patria = 'Родина №' .
                $issue->getMonth() .
                "'" .
                $issue->getYear() .
                ' ' .
                $patritem->getQuantity() .
                ' экз'
            ;

            $goods[] = $patria;
        }

        $names_list = join(', ', $goods);

        $permalink_id = $this->get('rg_api.encryptor')->encryptOrderId($order->getId());
        $url = join('', [
            $this->getParameter('domain'),
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

    /**
     * @param Order $order
     * @param $items
     * @param $patritems
     * @return Response
     */
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
            $this->getParameter('domain'),
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

    /**
     * @param Legal $legal
     * @return string
     */
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

    /**
     * @param \stdClass $order_details
     * @return Legal
     * @throws OrderException
     */
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

