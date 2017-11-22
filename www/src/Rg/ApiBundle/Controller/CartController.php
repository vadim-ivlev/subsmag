<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Cart\Cart;
use Rg\ApiBundle\Cart\CartException;
use Rg\ApiBundle\Cart\CartItem;
use Rg\ApiBundle\Cart\CartPatritem;
use Rg\ApiBundle\Entity\Area;
use Rg\ApiBundle\Entity\Delivery;
use Rg\ApiBundle\Entity\Edition;
use Rg\ApiBundle\Entity\Issue;
use Rg\ApiBundle\Entity\Medium;
use Rg\ApiBundle\Entity\Pin;
use Rg\ApiBundle\Entity\Product;
use Rg\ApiBundle\Entity\Promo;
use Rg\ApiBundle\Entity\Tariff;
use Rg\ApiBundle\Entity\Zone;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends Controller implements SessionHasCartController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        /** @var Cart $cart */
        $cart = unserialize($session->get('cart'));

        $promo = $this->getPromoOrNull($request, $session);

        $detailed_cart = $this->detailCart($cart, $promo);

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

        $promo = $this->getPromoOrNull($request, $session);

        $detailed_cart = $this->detailCart($cart, $promo);

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

        $promo = $this->getPromoOrNull($request, $session);

        $detailed_cart = $this->detailCart($cart, $promo);

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

        $promo = $this->getPromoOrNull($request, $session);

        $detailed_cart = $this->detailCart($cart, $promo);

        return (new Out())->json($detailed_cart);
    }

    public function applyPromoAction(Request $request, SessionInterface $session)
    {
        $data = json_decode(
            $request->getContent()
        );

        if (!isset($data->promocode)) {
            $error = 'no promocode given';
            return (new Out())->json(['error' => $error,]);
        }

        $promocode = $data->promocode;

        if (!$this->isValidPromocode($promocode)) {
            $error = 'Промокод содержит недопустимые символы. Допускаются 0-9A-z_-%/';
            return (new Out())->json(['error' => $error,]);
        }

        try {
            $promo = $this->fetchPromoFromDB($promocode, $request);
        } catch (\Exception $e) {
            return (new Out())->json(['error' => $e->getMessage(),]);
        }

        ## с промокодом, видимо, всё в порядке.
        // write it to session for next generations
        $session->set('promocode', $promocode);

        /** @var Cart $cart */
        $cart = unserialize($session->get('cart'));

        $detailed_cart = $this->detailCart($cart, $promo);

        return (new Out())->json($detailed_cart);
    }

    private function fetchPromoFromDB(string $promocode, Request $request)
    {
        $raw_promo = explode('/', $promocode);
        $code = $raw_promo[0];

        /** @var Promo $promo */
        $promo = $this->getDoctrine()->getRepository('RgApiBundle:Promo')
            ->findOneBy(['code' => $code]);

        if (is_null($promo)) {
            $error = 'Промокод не найден';
            throw new \Exception($error);
        }

        ## всё, что не так с промокодом -- объясняю подробно
        // 0. Наш промокод с пин-кодами?
        if ($promo->getPins()->count() > 0) {
            // пользователь отправил пинкод?
            if (count($raw_promo) < 2) {
                $error = 'Пин-код промокода не передан.';
                throw new \Exception($error);
            }

            // пин есть у нас в таблице?
            $user_pin = trim($raw_promo[1]);

            if (!$this->isValidPin($user_pin)) {
                $error = 'Пин-код промокода не передан или неправильный.';
                throw new \Exception($error);
            }

            $pins = $promo->getPins()->filter(
                function (Pin $pin) use($user_pin) {
                    return $pin->getValue() == $user_pin;
                }
            );

            if ($pins->count() != 1) {
                $error = 'Пин-код не найден.';
                throw new \Exception($error);
            }

            $pin = $pins->current();

            // пин уже использован?
            if ($pin->getOrder() != null) {
                $error = 'Пин-код уже активирован.';
                throw new \Exception($error);
            }
        }

        // 1. area, zone
        if (!$promo->getIsCountrywide()) {
            $from_front_id = $this->getFrontId($request);
            if (is_null($from_front_id)) {
                $error = 'Регион не определён. Видимо, его нет в cookie.';
                throw new \Exception($error);
            }

            /** @var null|Area $area */
            $user_area = $this->getArea($from_front_id);
            if (is_null($user_area)) {
                $error = 'В базе не найден регион с id ' . $from_front_id;
                throw new \Exception($error);
            }

            /** @var Area $promo_area */
            $promo_area = $promo->getArea();
            if (!is_null($promo_area)) {
                if ($user_area->getId() != $promo_area->getId()) {
                    $error = 'Промокод действителен только для ' . $promo_area->getName()
                        . ', ваш регион определён как ' . $user_area->getName();
                    throw new \Exception($error);
                }
            }

            $promo_zones = $promo->getZones();
            if ($promo_zones->count() > 0) {
                /** @var Zone $user_zone */
                $user_zone = $user_area->getZone();

                if (!$promo_zones->contains($user_zone)) {
                    $error = 'Промокод недействителен для вашей группы регионов, определённой как '
                        . $user_zone->getName();
                    throw new \Exception($error);
                }
            }
        }

        // 2. is active?
        if ($promo->getIsActive() == false) {
            $error = "Промокод не активен.";
            throw new \Exception($error);
        }

        // 3. has started, ended?
        $start = $promo->getStart();
        $end = $promo->getEnd();
        if (!is_null($start) && !is_null($end)) {
            $date = new \DateTime(date('Y-m-d'));
            if ($date < $start) {
                $error = "Период действия промокода ещё не начался.";
                throw new \Exception($error);
            }
            if ($date > $end) {
                $error = "Период действия промокода уже закончился.";
                throw new \Exception($error);
            }
        }

        // 4. amount is not exhausted?
        $amount = $promo->getAmount();
        if (!is_null($amount)) {
            $sold = $promo->getSold();

            if ($amount <= $sold) {
                $error = "Достигнут лимит активаций по этому промокоду.";
                throw new \Exception($error);
            }
        }

        return $promo;
    }

    private function detailCart(Cart $cart, Promo $promo = null)
    {

        ### детализировать подписные позиции
        $detailed_products = array_map(
            function (CartItem $cart_item) use($promo) {
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

                ## работаем со скидкой
                $is_promoted = false;
                if (is_null($promo)) {
                    $discount = 0;
                } else {
                    if ($this->doesPromoFitTariff($promo, $tariff)) {
                        $discount = $promo->getDiscount();
                        $is_promoted = true;
                    } else
                        $discount = 0;
                }

                $discount_coef = (100 - $discount) / 100;

                $price = $tariff->getCataloguePrice() + $tariff->getDeliveryPrice();

                $new_cost = $cost * $discount_coef;

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
                    'price' => $price, // тарифная цена
                    'cost' => $cost, // цена одной штуки позиции (тарифная * количество тайм-юнитов) без скидки
                    'is_promoted' => $is_promoted,
                    'old_cost' => $cost,
                    'discount' => $discount,
                    'discount_coef' => $discount_coef,
                    'new_cost' => $new_cost,
                    'debug' => [
                        'tu' => $tariff->getTimeunit()->getId(),
                        'p' => $product->getId(),
                        'zone' => $tariff->getZone()->getId(),
                    ],
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

        return [
            'products' => $detailed_products,
            'archives' => $detailed_archives,
        ];
    }

    private function isValidPromocode(string $promocode)
    {
        if (strlen($promocode) > 255 or empty($promocode)) return false;
        if (preg_match('#[^0-9A-Za-z_%/-]#', $promocode)) return false;
        return true;
    }

    private function getFrontId($request): int
    {
        //TODO: works only on prod with cookies
        $rg_geo_data = $request->cookies->get('rg_geo_data') ?? $request->cookies->get('rg_user_region');

        // Test purposes only. Remove!
        // московская кука
        $rg_geo_data = "%7B%22id%22%3A201%2C%22rgId%22%3A3132%2C%22link%22%3A%22%5C%2Fregion%5C%2Fcfo%5C%2Fstolica%5C%2F%22%2C%22originName%22%3A%22%5Cu041c%5Cu043e%5Cu0441%5Cu043a%5Cu0432%5Cu0430%22%2C%22originPrepositionalName%22%3A%22%5Cu041c%5Cu043e%5Cu0441%5Cu043a%5Cu0432%5Cu0435%22%2C%22originGenitiveName%22%3A%22%5Cu041c%5Cu043e%5Cu0441%5Cu043a%5Cu0432%5Cu044b%22%2C%22rubricName%22%3A%22%5Cu041c%5Cu043e%5Cu0441%5Cu043a%5Cu0432%5Cu0430%22%2C%22rubricPrepositionalName%22%3A%22%5Cu041c%5Cu043e%5Cu0441%5Cu043a%5Cu0432%5Cu0435%22%2C%22rubricGenitiveName%22%3A%22%5Cu041c%5Cu043e%5Cu0441%5Cu043a%5Cu0432%5Cu044b%22%7D";
        // владивостокская кука
//        $rg_geo_data = "%7B%22id%22%3A96%2C%22rgId%22%3A3718%2C%22link%22%3A%22%5C%2Fregion%5C%2Fdfo%5C%2Fprimorie%5C%2Fvladivostok%5C%2F%22%2C%22originName%22%3A%22%5Cu0412%5Cu043b%5Cu0430%5Cu0434%5Cu0438%5Cu0432%5Cu043e%5Cu0441%5Cu0442%5Cu043e%5Cu043a%22%2C%22originPrepositionalName%22%3A%22%5Cu0412%5Cu043b%5Cu0430%5Cu0434%5Cu0438%5Cu0432%5Cu043e%5Cu0441%5Cu0442%5Cu043e%5Cu043a%5Cu0435%22%2C%22originGenitiveName%22%3A%22%5Cu0412%5Cu043b%5Cu0430%5Cu0434%5Cu0438%5Cu0432%5Cu043e%5Cu0441%5Cu0442%5Cu043e%5Cu043a%5Cu0430%22%2C%22rubricName%22%3A%22%5Cu0412%5Cu043b%5Cu0430%5Cu0434%5Cu0438%5Cu0432%5Cu043e%5Cu0441%5Cu0442%5Cu043e%5Cu043a%22%2C%22rubricPrepositionalName%22%3A%22%5Cu0412%5Cu043b%5Cu0430%5Cu0434%5Cu0438%5Cu0432%5Cu043e%5Cu0441%5Cu0442%5Cu043e%5Cu043a%5Cu0435%22%2C%22rubricGenitiveName%22%3A%22%5Cu0412%5Cu043b%5Cu0430%5Cu0434%5Cu0438%5Cu0432%5Cu043e%5Cu0441%5Cu0442%5Cu043e%5Cu043a%5Cu0430%22%7D";

        if (!$rg_geo_data) {
            return null;
        }

        $from_front_id = (json_decode( urldecode($rg_geo_data) ))->id;

        return $from_front_id;
    }

    private function getArea($from_front_id)
    {
        $area = $this->getDoctrine()->getRepository('RgApiBundle:Area')
            ->findOneBy(['from_front_id' => $from_front_id]);

        return $area;
    }

    private function isValidPin($pin)
    {
        if (strlen($pin) < 1) return false;

        return true;
    }

    private function doesPromoFitTariff(Promo $p, Tariff $t)
    {
        if (!$p->getIsCountrywide()) {
            $promo_area = $p->getArea();
            if (!is_null($promo_area)) {
                // промокод регионированный
                if ($promo_area->getZone()->getId() != $t->getZone()->getId()) return false;
            } else {
                // промо зонированный
                if (!$p->getZones()->contains($t->getZone())) return false;
            }
        }

        if ($p->getTimeunit()->getId() != $t->getTimeunit()->getId()) return false;

        if (!$p->getProducts()->contains($t->getProduct())) return false;

        return true;
    }

    /**
     * Вернёт null, если промокод либо протух, либо ещё не добавлен
     * @param Request $request
     * @param SessionInterface $session
     * @return null|Promo
     */
    private function getPromoOrNull(Request $request, SessionInterface $session)
    {
        $promocode = $session->get('promocode');
        if (is_null($promocode)) return null;

        $promo = null;

        try {
            $promo = $this->fetchPromoFromDB($promocode, $request);
        } catch (\Exception $e) {
            // промокод протух, наверное?
            $session->remove('promocode');
        }

        return $promo;
    }
}
