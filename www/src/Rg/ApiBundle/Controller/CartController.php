<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Cart\Cart;
use Rg\ApiBundle\Cart\CartItem;
use Rg\ApiBundle\Cart\CartPatritem;
use Rg\ApiBundle\Entity\Delivery;
use Rg\ApiBundle\Entity\Edition;
use Rg\ApiBundle\Entity\Issue;
use Rg\ApiBundle\Entity\Medium;
use Rg\ApiBundle\Entity\Product;
use Rg\ApiBundle\Entity\Tariff;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends Controller implements SessionHasCartController
{
    const MONTH = 2048;
    public function indexAction(Request $request, SessionInterface $session)
    {
        /** @var Cart $cart */
        $cart = unserialize($session->get('cart'));

        $detailed_cart = $this->detailCart($cart);

        return (new Out())->json($detailed_cart);
    }

    public function emptyAction(SessionInterface $session)
    {
        $cart = new Cart();

        $session->set('cart', serialize($cart));

        return (new Out())->json($cart);
    }

    public function addAction(Request $request, SessionInterface $session)
    {
        /** @var Cart $cart */
        $cart = unserialize($session->get('cart'));

        # # #
        $input_items = json_decode(
            $request->getContent()
        );

        // создать итемсы для каждого продукта
        $products = $input_items->products;

        $cart_items = array_map(
            function (\stdClass $product) {
                // превратить анонима в продуктовую позицию корзины
                $cart_item = new CartItem(
                    $product->first_month,
                    $product->duration,
                    $product->year,
                    $product->tariff,
                    $product->quantity
                );

                return $cart_item;
            },
            $products
        );

        // создать патритемсы для каждого архива
        $archives = $input_items->archives;
        $cart_patritems = array_map(
            function (\stdClass $archive) {
                // превратить анонима в архивную позицию корзины
                $cart_patritem = new CartPatritem(
                    $archive->quantity,
                    $archive->patriff
                );

                return $cart_patritem;
            },
            $archives
        );

        // добавить в корзину
        /** @var CartItem $cart_item */
        foreach ($cart_items as $cart_item) {
            $cart->addItem($cart_item);
        }

        /** @var CartPatritem $cart_patritem */
        foreach ($cart_patritems as $cart_patritem) {
            $cart->addPatritem($cart_patritem);
        }
        # # #

        $session->set('cart', serialize($cart));

        $detailed_cart = $this->detailCart($cart);

        return (new Out())->json($detailed_cart);
    }

    public function updateAction(Request $request, SessionInterface $session)
    {
        /** @var Cart $cart */
        $cart = unserialize($session->get('cart'));

        ### do update action
        $keys_to_update = json_decode(
            $request->getContent()
        );

        $product_quantities = $keys_to_update->products;
        $cart->updateMultipleItemsByKeys($product_quantities);

        $archive_quantities = $keys_to_update->archives;
        $cart->updateMultiplePatritemsByKeys($archive_quantities);

        ###

        $session->set('cart', serialize($cart));

        $detailed_cart = $this->detailCart($cart);

        return (new Out())->json($detailed_cart);
    }

    public function removeAction(Request $request, SessionInterface $session)
    {
        /** @var Cart $cart */
        $cart = unserialize($session->get('cart'));

        ### do some remove action
        $keys_to_remove = json_decode(
            $request->getContent()
        );
        $product_keys = $keys_to_remove->products;
        $archive_keys = $keys_to_remove->archives;

        $cart->removeMultipleItemsByKeys($product_keys);
        $cart->removeMultiplePatritemsByKeys($archive_keys);

        ###

        $session->set('cart', serialize($cart));

        $detailed_cart = $this->detailCart($cart);

        return (new Out())->json($detailed_cart);
    }

    private function detailCart(Cart $cart)
    {
        //
        ### детализировать подписные позиции
        $detailed_products = array_map(
            function (CartItem $cart_item) {
                $doctrine = $this->getDoctrine();
                $tariff = $doctrine
                    ->getRepository('RgApiBundle:Tariff')
                    ->findOneBy(['id' => $cart_item->getTariff()]);

                /** @var Product $product */
                $product = $tariff->getProduct();

                $images = $product->getEditions()->map(
                    function (Edition $edition) {
                        return $edition->getImage();
                    }
                )->toArray();

                /** @var Medium $medium */
                $medium = $tariff->getMedium();

                /** @var Delivery $delivery */
                $delivery = $tariff->getDelivery();

                $duration = $cart_item->getDuration();

                $cost = $this->get('rg_api.product_cost_calculator')
                    ->calculateItemCost($tariff, $duration);

                return [
                    'image' => $images,
                    'is_kit' => $product->getIsKit(),
                    'first_month' => $cart_item->getFirstMonth(),
                    'year' => $cart_item->getYear(),
                    'duration' => $duration,
                    'medium' => $medium->getName(),
                    'delivery' => $delivery->getName(),
                    'quantity' => $cart_item->getQuantity(),
                    'price' => $tariff->getPrice(),
                    'cost' => $cost,
                ];
            },
            $cart->getCartItems()
        );

        ### detail archive items
        $detailed_archives = array_map(
            function (CartPatritem $cart_patritem) {
                $doctrine = $this->getDoctrine();
                $patriff = $doctrine
                    ->getRepository('RgApiBundle:Patriff')
                    ->findOneBy(['id' => $cart_patritem->getPatriff()]);

                /** @var Issue $issue */
                $issue = $patriff->getIssue();

                /** @var Delivery $delivery */
                $delivery = $patriff->getDelivery();

                $price = $patriff->getPrice();
                $quantity = $cart_patritem->getQuantity();
                $cost = $price * $quantity;

                return [
                    'month' => $issue->getMonth(),
                    'year' => $issue->getYear(),
                    'image' => $issue->getImage(),
                    'delivery' => $delivery->getName(),
                    'quantity' => $quantity,
                    'price' => $price,
                    'cost' => $cost,
                ];
            },
            $cart->getCartPatritems()
        );

        return [
            'products' => $detailed_products,
            'archives' => $detailed_archives,
        ];
    }

}
