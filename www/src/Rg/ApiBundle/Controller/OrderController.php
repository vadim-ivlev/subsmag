<?php

namespace Rg\ApiBundle\Controller;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Rg\ApiBundle\Cart\Cart;
use Rg\ApiBundle\Cart\CartItem;
use Rg\ApiBundle\Cart\CartPatritem;
use Rg\ApiBundle\Entity\Item;
use Rg\ApiBundle\Entity\Order;
use Rg\ApiBundle\Entity\Patritem;
use Rg\ApiBundle\Entity\Tariff;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderController extends Controller
{
    const MONTH = 2048;

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

        ### показать сформированный заказ
        $resp = [
            'order' => $order->getId(),
            'total' => $order->getTotal(),
            'items' => array_map(
                function (Item $item) {
                    return $item->getId();
                },
                $items
            ),
            'patritems' => array_map(
                function (Patritem $patritem) {
                    return $patritem->getId();
                },
                $patritems
            ),
        ];

        return (new Out())->json($resp);

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
                    ->findOneBy(['id' => $cart_item->getTariff()]);
                $item->setTariff($tariff);

                $calculator = $this->get('rg_api.product_cost_calculator');
                $timeunit_amount = $calculator->calculateTimeunitAmount($tariff, $cart_item->getDuration());
                $item->setTimeunitAmount($timeunit_amount);

                $cost = $calculator->calculateItemCost($tariff, $cart_item->getDuration());
                $item->setCost($cost);

                $sale = $doctrine
                    ->getRepository('RgApiBundle:Sale')
                    ->findOneBy(['id' => $cart_item->getSale()]);
                $item->setSale($sale);

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
}
