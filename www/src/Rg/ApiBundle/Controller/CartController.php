<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Cart\Cart;
use Rg\ApiBundle\Cart\CartItem;
use Rg\ApiBundle\Cart\CartPatritem;
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

        return (new Out())->json($cart);
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
                    $product->id,
                    $product->medium,
                    $product->delivery,
                    $product->sale,
                    $product->tariff,
                    $product->duration,
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
                    $archive->id,
                    $archive->delivery,
                    $archive->year,
                    $archive->issue,
                    $archive->quantity,
                    $archive->patriff
                );

                return $cart_patritem;
            },
            $archives
        );

        // добавить в корзину
        foreach ($cart_items as $cart_item) {
            $cart->addItem($cart_item);
        }

        foreach ($cart_patritems as $cart_patritem) {
            $cart->addPatritem($cart_patritem);
        }
        # # #

        $session->set('cart', serialize($cart));

        return (new Out())->json($cart);
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

        return (new Out())->json($cart);
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

        return (new Out())->json($cart);
    }


}
