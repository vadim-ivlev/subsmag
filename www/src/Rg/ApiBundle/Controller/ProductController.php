<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Entity\Delivery;
use Rg\ApiBundle\Entity\Edition;
use Rg\ApiBundle\Entity\Product;
use Rg\ApiBundle\Entity\Tariff;
use Rg\ApiBundle\Entity\Timeblock;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;

class ProductController extends Controller
{
    const MOSCOW = 4;

    public function indexAction(Request $request)
    {
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $from_front_id = $request->query->get('area_id', self::MOSCOW);

        $area = $em->getRepository('RgApiBundle:Area')->findOneBy(['id' => $from_front_id]);

        if (!$area) {
            $arrError = [
                'status' => "error",
                'description' => 'Регион не найден.',
            ];
            return $out->json($arrError);
        }

        $product_container = $em->getRepository('RgApiBundle:Product')
            ->getProductsWithMinPricesByArea($from_front_id);

        if (!$product_container) {
            $arrError = [
                'status' => "error",
                'description' => 'Комплекты не найдены!',
            ];
            return $out->json($arrError);
        }

        $prods_with_media = array_map(
            function (array $item) {
                $product = $item[0];

                ########################
                //носители
                ########################
                $all_media_for_product = array_map(
                    function (Tariff $tariff) {
                        $medium = $tariff->getMedium();

                        return serialize([
                            'id' => $medium->getId(),
                            'alias' => $medium->getAlias(),
                            'description' => $medium->getDescription(),
                            'name' => $medium->getName(),
                        ]);
                    },
                    iterator_to_array($product->getTariffs())
                );

                $unique_media = array_map(
                    function($item) { return unserialize($item); },
                    array_values(array_unique($all_media_for_product))
                );
                ########################

                ######
                //доставка
                ######
                $unique_media_with_delivs = [];
                foreach ($unique_media as $unique_medium) {
                    //по медиум-ид
                    $filtered_tariffs = array_filter(
                        iterator_to_array($product->getTariffs()),
                        function (Tariff $tariff) use ($unique_medium) {
                            return $tariff->getMedium()->getId() == $unique_medium['id'];
                        }
                    );

                    $all_deliveries = array_map(
                        function (Tariff $tariff) {
                            $delivery = $tariff->getDelivery();

                            return serialize([
                                'id' => $delivery->getId(),
                                'alias' => $delivery->getAlias(),
                                'description' => $delivery->getDescription(),
                                'name' => $delivery->getName(),
                            ]);
                        },
                        $filtered_tariffs
                    );

                    $unique_delivs = array_map(
                        function($item) { return unserialize($item); },
                        array_values(array_unique($all_deliveries))
                    );

                    ####
                    //таймблоки
                    ####
                    $delivs_with_timeblocks = array_map(
                        function (array $delivery) use ($product, $unique_medium) {
                            $filtered_by_medium_and_delivs_tariffs = array_filter(
                                iterator_to_array($product->getTariffs()),
                                function (Tariff $tariff) use ($unique_medium, $delivery) {
                                    $criterion = ($tariff->getMedium()->getId() == $unique_medium['id']) and
                                        ($tariff->getDelivery()->getId() == $delivery['id']);

                                    return $criterion;
                                }
                            );

                            $all_timeblocks_for_tariff = array_map(
                                function (Tariff $tariff) {
                                    $timeblocks = $tariff->getTimeblocks();


                                    if ($timeblocks->isEmpty()) return null;
                                    return 'bb';

                                    $normalized_timeblocks = array_map(
                                        function (Timeblock $timeblock) use ($tariff) {

                                            $duration = $timeblock->getDuration();

                                            $item_price = $tariff->getPrice();

                                            $price = ($duration < 6) ? $item_price * $duration : $item_price;

                                            return [
                                                'id' => $timeblock->getId(),
                                                'first_month' => $timeblock->getFirstMonth(),
                                                'duration' => $duration,
                                                'year' => $timeblock->getYear(),
                                                'price' => $price,
                                            ];
                                        },
                                        iterator_to_array($timeblocks)
                                    );

                                    return $normalized_timeblocks;
                                },
                                $filtered_by_medium_and_delivs_tariffs
                            );

                            $cleared_from_empty_timeblocks = array_filter(
                                $all_timeblocks_for_tariff,
                                function ($tb) {
                                    return $tb !== null;
                                }
                            );

                            $delivery['periods'] = array_values($cleared_from_empty_timeblocks);

                            return $delivery;
                        },
                        $unique_delivs
                    );
                    ####

                    $unique_medium['deliveries'] = $delivs_with_timeblocks;

                    $unique_media_with_delivs[] = $unique_medium;
                }
                ######


                $editions = array_map(
                    [$this->get('rg_api.edition_normalizer'), 'convertToArray'],
                    iterator_to_array($product->getEditions())
                );

                $item['editions'] = $editions;
                $item['media'] = $unique_media_with_delivs;

                return $item;
            },
            $product_container
        );


//        dump($prods_with_media);die;

        return  $out->json($prods_with_media);
    }

    public function showAction($id)
    {
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('RgApiBundle:Product')->find($id);

        if (!$product) {
            $arrError = [
                'status' => "error",
                'description' => 'Комплект не найден!',
            ];
            return $out->json($arrError);
        }

        $editions = array_map(
            [$this->get('rg_api.edition_normalizer'), 'convertToArray'],
            iterator_to_array($product->getEditions())
        );

        $prod = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'postal_index' => $product->getPostalIndex(),
            'is_kit' => $product->getIsKit(),
            'is_archive' => $product->getIsArchive(),
            'outer_link' => $product->getOuterLink(),
            'sort' => $product->getSort(),
            'editions' => $editions,
        ];

        $response = $out->json($prod);

        return $response;
    }

    private function appendTariffs(array $product) {
        $tariffs = array_map(
            function (Tariff $tariff) {
                $period = $tariff->getPeriod();
                $delivery = $tariff->getDelivery();
                $zone = $tariff->getZone();
                $medium = $tariff->getMedium();

                return [
                    'id' => $tariff->getId(),
                    'medium' => [
                        'id' => $medium->getId(),
                        'alias' => $medium->getAlias(),
                        'description' => $medium->getDescription(),
                        'name' => $medium->getName(),
                    ],
                    'delivery' => [
                        'id' => $delivery->getId(),
                        'name' => $delivery->getName(),
                        'description' => $delivery->getDescription(),
                    ],
                    'period' => [
                        'id' => $period->getId(),
                        'name' => $period->getName(),
                    ],
                    'zone' => [
                        'id' => $zone->getId(),
                        'name' => $zone->getName(),
                        'from_front_id' => $zone->getFromFrontId(),
                    ],
                    'price' => $tariff->getPrice(),
                ];
            },
            iterator_to_array($product[0]->getTariffs())
        );

        $product['tariffs'] = $tariffs;

        return $product;

    }

    public function createAction(Request $request)
    {
        return (new Out())->json( ['ask' => 'wait for a while, please.']);
    }

    public function editAction($id, Request $request)
    {
        return (new Out())->json( ['ask' => 'wait for a while, please.']);
    }

}
