<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Entity\Item;
use Rg\ApiBundle\Entity\Order;
use Rg\ApiBundle\Entity\Product;
use Rg\ApiBundle\Entity\Tariff;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\HttpFoundation\Session\Session;

class OrderController extends Controller
{
    const MONTH = 2048;

    public function createAction(Request $request)
    {
        $session = $request->getSession();

        $input_order = json_decode(
            $request->getContent()
        );

        //отделить продукты от архивов
        $products = $input_order->products;
        $archives = $input_order->archives;

        $order = new Order();
        $order->setDate(new \DateTime());
        $order->setAddress('');
        $order->setIsPaid(false);

        $doctrine = $this->getDoctrine();

        $items = array_map(
            function (\stdClass $product_order) use ($doctrine, $order) {
                $item = new Item();

                $item->setQuantity($product_order->quantity);

                $tariff = $doctrine
                    ->getRepository('RgApiBundle:Tariff')
                    ->findOneBy(['id' => $product_order->tariff]);
                $item->setTariff($tariff);

                $timeunit_amount = $this->calculateTimeunitAmount($tariff, $product_order->duration);
                $item->setTimeunitAmount($timeunit_amount);

                // вычислить стоимость единицы позиции по формуле
                // cost = tu_amount * tariff.price
                $cost = $timeunit_amount * $tariff->getPrice();
                $item->setCost($cost);

                $sale = $doctrine
                    ->getRepository('RgApiBundle:Sale')
                    ->findOneBy(['id' => $product_order->sale->id]);
                $item->setSale($sale);

                $item->setOrder($order);

                return $item;
            },
            $products
        );

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
            'session' => $session->getId() . $session->getName(),
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
