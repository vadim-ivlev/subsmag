<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Cart\Cart;
use Rg\ApiBundle\Cart\CartException;
use Rg\ApiBundle\Cart\CartItem;
use Rg\ApiBundle\Cart\CartPatritem;
use Rg\ApiBundle\Entity\Delivery;
use Rg\ApiBundle\Entity\Edition;
use Rg\ApiBundle\Entity\Issue;
use Rg\ApiBundle\Entity\Medium;
use Rg\ApiBundle\Entity\Product;
use Rg\ApiBundle\Entity\Promo;
use Rg\ApiBundle\Exception\PromoException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends Controller implements SessionHasCartController
{
    const COUNTER = 'promo:tries:counter';
    const MAX_TRIES = 200;

//    private $doctrine;
//    public function __construct(Registry $doctrine)
//    {
//        $this->doctrine = $doctrine;
//    }

    /**
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function indexAction(Request $request, SessionInterface $session)
    {
        /** @var Cart $cart */
        $cart = unserialize($session->get('cart'));

        $promo = $this->get('rg_api.promo_fetcher')->getPromoOrNull($request, $session);

        $detailed_cart = $this->detailCart($cart, $promo);

        return (new Out())->json($detailed_cart);
    }

    /**
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function emptyAction(SessionInterface $session)
    {
        $cart = new Cart();

        $session->set('cart', serialize($cart));

        return (new Out())->json($cart);
    }

    /**
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function addAction(Request $request, SessionInterface $session)
    {
        /** @var Cart $cart */
        $cart = unserialize($session->get('cart'));

        # # #
        $input_items = json_decode(
            $request->getContent()
        );
        if (is_null($input_items)) {
            $error = [
                'error' => 'Not valid JSON given.',
//                'description' => $e->getMessage(),
            ];

            return (new Out())->json($error);
        }

        // создать итемсы для каждого продукта
        $products = $input_items->products;

        try {
            $cart_items = array_map(
                function (\stdClass $product) {
                    // превратить анонима в продуктовую позицию корзины
                    $cartItemValidator = $this->get('rg_api.cartitem_validator');

                    if (!($tariff_id = $cartItemValidator->validateId((int) $product->tariff))) {
                        $message = 'Tariff id should be an integer greater than 0, ' . $tariff_id . ' given in ';
                        $dump = join(', ', (array) $product);
                        throw new CartException($message . $dump);
                    }

                    $doctrine = $this->getDoctrine();
                    $tariff = $doctrine
                        ->getRepository('RgApiBundle:Tariff')
                        ->findOneBy(
                            [
                                'id' => $tariff_id
                            ]);

                    if (is_null($tariff)) {
                        throw new CartException('Tariff not found.');
                    }

                    // используем сервис валидации
                    $cartItemValidator->validateProduct($product, $tariff);

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
        } catch (CartException $e) {
            $error = [
                'error' => 'Not valid cart item given.',
                'description' => $e->getMessage(),
            ];

            return (new Out())->json($error);
        }

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

        $promo = $this->get('rg_api.promo_fetcher')->getPromoOrNull($request, $session);

        $detailed_cart = $this->detailCart($cart, $promo);

        return (new Out())->json($detailed_cart);
    }

    /**
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
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

        $promo = $this->get('rg_api.promo_fetcher')->getPromoOrNull($request, $session);

        $detailed_cart = $this->detailCart($cart, $promo);

        return (new Out())->json($detailed_cart);
    }

    /**
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
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

        $promo = $this->get('rg_api.promo_fetcher')->getPromoOrNull($request, $session);

        $detailed_cart = $this->detailCart($cart, $promo);

        return (new Out())->json($detailed_cart);
    }

    /**
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function applyPromoAction(Request $request, SessionInterface $session)
    {
        $have_you_questions = ' Есть вопросы? Позвоните нам 8(800)100-11-13, звонок бесплатный по России.';
        $counter = $session->get(self::COUNTER) ?? 1;
        if ($counter == self::MAX_TRIES) {
            $session->set('promo:tries:access', time());
        }
        if ($counter > self::MAX_TRIES) {
            $last_access_time = $session->get('promo:tries:access');
            if ((time() - $last_access_time) < 30) {
                return (new Out())->json(['error' => 'Подождите, пожалуйста, 30 секунд.',]);
            } else {
                $session->set(self::COUNTER, $counter = 1);
            }
        }

        $data = json_decode(
            $request->getContent()
        );

        if (!isset($data->promocode)) {
            $session->set(self::COUNTER, $counter + 1);
            $error = 'no promocode given';
            return (new Out())->json(['error' => $error,]);
        }

        $promocode = $data->promocode;

        if (!$this->get('rg_api.promo_fetcher')->isValidPromocode($promocode)) {
            $session->set(self::COUNTER, $counter + 1);
            $error = 'Промокод содержит недопустимые символы. Проверьте, пожалуйста, ещё раз строку.' . $have_you_questions;
            return (new Out())->json(['error' => $error,]);
        }

        try {
            $promo = $this->get('rg_api.promo_fetcher')->fetchPromoFromDB($promocode, $request);
        } catch (PromoException $e) {
            $session->set(self::COUNTER, $counter + 1);
            return (new Out())->json(['error' => $e->getMessage() . $have_you_questions,]);
        }

        ## с промокодом, видимо, всё в порядке.
        // write it to session for next generations
        $session->set('promocode', $promocode);

        /** @var Cart $cart */
        $cart = unserialize($session->get('cart'));

        $detailed_cart = $this->detailCart($cart, $promo);

        return (new Out())->json($detailed_cart);
    }

    /**
     * Наверное, главная магия происходит здесь. Считаем стоимости позиций, отдаём фронту.
     * Если есть скидка в промокоде, применяем её, чтобы показать покупателю.
     * @param Cart $cart
     * @param Promo|null $promo
     * @return array
     */
    private function detailCart(Cart $cart, Promo $promo = null)
    {
        $has_cart_any_promoted = false;

        ### детализировать подписные позиции
        $detailed_products = array_map(
            function (CartItem $cart_item) use($promo, &$has_cart_any_promoted) {
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

                ## Работаем со скидкой
                ### Скидка в рублях ВЫЧИТАЕТСЯ из тарифной цены. Раньше была в процентах и умножалась.
                $is_promoted = false;
                if (is_null($promo)) {
                    $discount = 0;
                } else {
                    if ($this->get('rg_api.promo_fetcher')->doesPromoFitTariff($promo, $tariff)) {
                        $discount = $promo->getDiscount();
                        $is_promoted = true;
                        $has_cart_any_promoted = true;
                    } else
                        $discount = 0;
                }

                // тарифная цена без скидки, нужна фронту
                $price = $tariff->getCataloguePrice() + $tariff->getDeliveryPrice();

                // тарифная цена со скидкой
                $discounted_price = $price - $discount;

                // стоимость одной штуки позиции без скидки
                $cost = $this->get('rg_api.product_cost_calculator')
                    ->calculateItemCost($tariff, $duration);


                // стоимость одной штуки позиции со скидкой
                $discounted_cost = $this->get('rg_api.product_cost_calculator')
                    ->calculateItemCost($tariff, $duration, $discount);

                return [
                    'name' => $product->getName(),
                    'image' => $images,
                    'is_kit' => $product->getIsKit(),
                    'first_month' => $cart_item->getFirstMonth(),
                    'year' => $cart_item->getYear(),
                    'duration' => $duration,
                    'medium' => $medium->getName(),
                    'delivery' => $delivery->getName(),
                    'quantity' => $cart_item->getQuantity(),
                    'price' => $price, // тарифная цена без скидки
                    'cost' => $cost, // цена одной штуки позиции (тарифная * количество тайм-юнитов) без скидки

                    'is_promoted' => $is_promoted,
                    'discounted_price' => $discounted_price,
                    'old_cost' => $cost,
                    'discount' => $discount,
                    'new_cost' => $discounted_cost,
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
                if (is_null($patriff)) {
                    throw new \Exception('there is no such a tariff for Rodina');
                }

                /** @var Issue $issue */
                $issue = $patriff->getIssue();

                /** @var Delivery $delivery */
                $delivery = $patriff->getDelivery();

                $price = ($patriff->getCataloguePrice() + $patriff->getDeliveryPrice());
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

        if (!is_null($promo)) {
            $promo_info = [
                'name' => $promo->getName(),
                'code' => $promo->getCode(),
//                'promocode' => '', // возьми из сессии
                'products' => $promo->getProducts()
                    ->map(
                        function (Product $p) {
                            return $p->getName();
                        }
                    )->toArray(),
            ];

            if (!$has_cart_any_promoted) {
                $promo_info['error'] = 'В корзине нет продуктов, соответствующих акции.';
            }
        }

        return [
            'products' => $detailed_products,
            'archives' => $detailed_archives,
            'promo' => $promo_info ?? null,
        ];
    }
}
