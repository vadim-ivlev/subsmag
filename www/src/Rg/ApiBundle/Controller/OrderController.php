<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Cart\Cart;
use Rg\ApiBundle\Cart\CartItem;
use Rg\ApiBundle\Entity\Item;
use Rg\ApiBundle\Entity\Order;
use Rg\ApiBundle\Entity\Product;
use Rg\ApiBundle\Entity\Tariff;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderController extends Controller
{
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

        $address = $order_details->address;
        $order->setAddress($address);

        $order->setIsPaid(false);

        $doctrine = $this->getDoctrine();

        ### подготовим массив подписных позиций
        $items = array_map(
            function (CartItem $cart_item) use ($doctrine, $order) {
                $item = new Item();

                $item->setQuantity($cart_item->getQuantity());

                $tariff = $doctrine
                    ->getRepository('RgApiBundle:Tariff')
                    ->findOneBy(['id' => $cart_item->getTariff()]);
                $item->setTariff($tariff);

                $timeunit_amount = $this->calculateTimeunitAmount($tariff, $cart_item->getDuration());
                $item->setTimeunitAmount($timeunit_amount);

                // вычислить стоимость единицы позиции по формуле
                // cost = tu_amount * tariff.price
                $cost = $timeunit_amount * $tariff->getPrice();
                $item->setCost($cost);

                $sale = $doctrine
                    ->getRepository('RgApiBundle:Sale')
                    ->findOneBy(['id' => $cart_item->getSale()]);
                $item->setSale($sale);

                $item->setOrder($order);

                return $item;
            },
            $cart->getCartItems()
        );

        ### подсчитаем количество экземпляров позиции
        $items_subtotal = array_reduce(
            $items,
            function ($total = 0, Item $item) {
                $item_total = $item->getCost() * $item->getQuantity();
                $total += $item_total;

                return $total;
            }
        );

        $patritems_subtotal = 0;
        $total = $items_subtotal + $patritems_subtotal;
        $order->setTotal($total);

        $em = $doctrine->getManager();
        $em->persist($order);
        array_walk(
            $items,
            function (Item $item) use ($em) {
                $em->persist($item);

                return $item;
            }
        );
        $em->flush();

        $resp = [
            'order' => $order->getId(),
            'total' => $order->getTotal(),
            'items' => array_map(
                function (Item $item) {
                    return $item->getId();
                },
                $items
            ),
            'session.order.counter' => $session->get('order/counter'),
            'session.order' => $session->get('order'),
        ];

        return (new Out())->json($resp);

    }

    private function calculateTimeunitAmount(Tariff $tariff, int $duration) {
        $bitmask = $tariff->getTimeunit()->getBitmask();

        if ($bitmask == self::MONTH) {
            return $duration;
        }
        return 1;
    }
}
