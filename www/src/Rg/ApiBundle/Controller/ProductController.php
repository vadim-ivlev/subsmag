<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Entity\Delivery;
use Rg\ApiBundle\Entity\Edition;
use Rg\ApiBundle\Entity\Product;
use Rg\ApiBundle\Entity\Tariff;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;

class ProductController extends Controller
{
    const MOSCOW = 1;

    public function indexAction(Request $request)
    {
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $from_front_id = $request->query->get('area_id', self::MOSCOW);

        $product_container = $em->getRepository('RgApiBundle:Product')
            ->getMedia($from_front_id);

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
                    array_unique($all_media_for_product)
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
                        array_unique($all_deliveries)
                    );

                    ####
                    //периоды
                    ####
                    $delivs_with_periods = array_map(
                        function (array $delivery) use ($product, $unique_medium) {
                            $filtered_tariffs = array_filter(
                                iterator_to_array($product->getTariffs()),
                                function (Tariff $tariff) use ($unique_medium, $delivery) {
                                    $criterion = ($tariff->getMedium()->getId() == $unique_medium['id']) and
                                        ($tariff->getDelivery()->getId() == $delivery['id']);

                                    return $criterion;
                                }
                            );

                            $all_periods = array_map(
                                function (Tariff $tariff) {
                                    $period = $tariff->getPeriod();

                                    return serialize([
                                        'id' => $period->getId(),
                                        'name' => $period->getName(),
                                        'price' => $tariff->getPrice(),
                                    ]);
                                },
                                $filtered_tariffs
                            );

                            $unique_periods = array_map(
                                function($item) { return unserialize($item); },
                                array_unique($all_periods)
                            );

                            $delivery['periods'] = $unique_periods;

                            return $delivery;
                        },
                        $unique_delivs
                    );
                    ####

                    $unique_medium['deliveries'] = $delivs_with_periods;

                    $unique_media_with_delivs[] = $unique_medium;
                }
                ######

                ######
                //минимальная цена
                ######
//                $min_price = '500';
//                $item['min_price'] = $min_price;
                ######

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
