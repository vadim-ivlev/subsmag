<?php

namespace Rg\ApiBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Rg\ApiBundle\Entity\Delivery;
use Rg\ApiBundle\Entity\Edition;
use Rg\ApiBundle\Entity\Product;
use Rg\ApiBundle\Entity\Sale;
use Rg\ApiBundle\Entity\Tariff;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;

class ProductController extends Controller
{

    public function indexAction(Request $request)
    {
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $from_front_id = $request->query->get('area_id', $this->getParameter('area'));

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

        ### attach editions
        $container_with_editions = array_map(
            function (array $item) {
                /** @var Product $product */
                $product = $item[0];

                $editions = $product->getEditions()->map(
                    function (Edition $edition) {
                        return [
                            'id' => $edition->getId(),
                            'name' => $edition->getName(),
                            'keyword' => $edition->getKeyword(),
                            'description' => $edition->getDescription(),
                            'frequency' => $edition->getFrequency(),
                            'image' => $edition->getImage(),
                        ];
                    }
                );

                $item['editions'] = $editions->toArray();

                return $item;
            },
            $product_container
        );

        ### attach available media for a product
        $container_with_media = array_map(
            function (array $item) {
                /** @var Product $product */
                $product = $item[0];

                $all_media_for_product = $product->getTariffs()->map(
                    function (Tariff $tariff) {
                        $medium = $tariff->getMedium();

                        return serialize([
                            'id' => $medium->getId(),
                            'alias' => $medium->getAlias(),
                            'description' => $medium->getDescription(),
                            'name' => $medium->getName(),
                        ]);
                    }
                );

                $unique_media = array_map(
                    function($item) { return unserialize($item); },
                    array_values(array_unique($all_media_for_product->toArray()))
                );

                $item['media'] = $unique_media;

                return $item;
            },
            $container_with_editions
        );

        ### attach available deliveries for a product with specified media
        $container_with_deliveries = array_map(
            function (array $item) {
                /** @var Product $product */
                $product = $item[0];

                $item['media'] = array_map(
                    function (array $unique_medium) use ($product) {
                        //по медиум-ид
                        $filtered_tariffs = $product->getTariffs()->filter(
                            function (Tariff $tariff) use ($unique_medium) {
                                return $tariff->getMedium()->getId() == $unique_medium['id'];
                            }
                        );

                        $all_deliveries = $filtered_tariffs->map(
                            function (Tariff $tariff) {
                                $delivery = $tariff->getDelivery();

                                return serialize([
                                    'id' => $delivery->getId(),
                                    'alias' => $delivery->getAlias(),
                                    'description' => $delivery->getDescription(),
                                    'name' => $delivery->getName(),
                                ]);
                            }
                        );

                        $unique_delivs = array_map(
                            function($item) { return unserialize($item); },
                            array_values(array_unique($all_deliveries->toArray()))
                        );

                        $unique_medium['deliveries'] = $unique_delivs;

                        return $unique_medium;
                    },
                    $item['media']
                );

                return $item;
            },
            $container_with_media
        );

        ### attach available sales for a product with specified media and deliveries
        $container_with_periods = array_map(
            function (array $item) use ($area) {
                /** @var Product $product */
                $product = $item[0];

                $item['media'] = array_map(
                    function (array $medium) use ($product, $area) {

                        if (empty($medium['deliveries'])) return $medium;

                        $medium['deliveries'] = array_map(
                            function (array $delivery) use ($product, $area, $medium) {
                                $sales = $product->getSales();

                                ############
                                /**
                                 * есть регионализированные продажи, есть общие.
                                 * если есть month-area для этого региона, взять его вместо общего.
                                 */
                                ############
                                $partitioned = $sales->partition(
                                    function ($key, Sale $sale) {
                                        return !($sale->getIsRegional());
                                    }
                                );

                                /** @var ArrayCollection $for_all_regions_sales */
                                $for_all_regions_sales = $partitioned[0];
                                /** @var ArrayCollection $region_specific_sales */
                                $region_specific_sales = $partitioned[1];

                                $area_checked = $region_specific_sales->filter(
                                    function (Sale $sale) use ($area) {
                                        $area_criterion = $sale->getArea()->getFromFrontId() == $area->getFromFrontId();

                                        return $area_criterion;
                                    }
                                );

                                $filtered_by_area_sales = $for_all_regions_sales->map(
                                    function (Sale $sale) use ($area_checked) {

                                        foreach ($area_checked->getIterator() as $replacer) {
                                            if ($sale->getMonth()->getId() == $replacer->getMonth()->getId()) {
                                                return $replacer;
                                            }
                                        }

                                        return $sale;
                                    }
                                );
                                ############
                                ############

                                $filtered_by_date_and_area_sales = $filtered_by_area_sales->filter(
                                    function (Sale $sale) {
                                        $start = $sale->getStart();
                                        $end = $sale->getEnd();

                                        $criterion = ((new \DateTime()) > $start) and ((new \DateTime()) < $end);

                                        return $criterion;
                                    }
                                );

                                // неоднозначно...
                                $normalized_sales = $filtered_by_date_and_area_sales->map(
                                    function (Sale $sale) {
                                        $month = $sale->getMonth();

                                        return [
                                            'id' => $sale->getId(),
                                            'number' => $month->getNumber(),
                                            'year' => $month->getYear(),
                                        ];
                                    }
                                );

                                $delivery['sales'] = array_values($normalized_sales->toArray());

                                #######
                                /**
                                 * timeunits and prices
                                 */
                                #######
                                $tariffs = $product->getTariffs();

                                $filtered_tariffs = $tariffs->filter(
                                    function (Tariff $tariff) use ($product, $medium, $delivery, $area) {
                                        $criterion = ($tariff->getMedium()->getId() == $medium['id']);
                                        $criterion = $criterion && ($tariff->getDelivery()->getId() == $delivery['id']);
                                        $criterion = $criterion && ($tariff->getZone()->getId() == $area->getZone()->getId());

                                        return $criterion;
                                    }
                                );

                                $timeunits_with_prices = $filtered_tariffs->map(
                                    function (Tariff $tariff) {
                                        $timeunit = $tariff->getTimeunit();
                                        $price = $tariff->getPrice();
                                        $id = $tariff->getId();

                                        $fake_discount = $timeunit->getDuration() == 1 ? 0 : 5.5;

                                        $norm = [
                                            'timeunit' => [
                                                'id' => $timeunit->getId(),
                                                'name' => $timeunit->getName(),
                                                'first_month' => $timeunit->getFirstMonth(),
                                                'duration' => $timeunit->getDuration(),
                                                'year' => $timeunit->getYear(),
                                            ],
                                            'id' => $id,
                                            'price' => $price,

                                            'discount' => $fake_discount,
                                        ];
                                        return $norm;
                                    }
                                );

                                $delivery['tariffs'] = array_values($timeunits_with_prices->toArray());
                                #######
                                #######

                                return $delivery;
                            },
                            $medium['deliveries']
                        );

                        return $medium;
                    },
                    $item['media']
                );

                ### clean luggage
                unset($item[0]);

                return $item;
            },
            $container_with_deliveries
        );



//        dump($show);die;

        return  $out->json($container_with_periods);
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

    public function createAction(Request $request)
    {
        return (new Out())->json( ['ask' => 'wait for a while, please.']);
    }

    public function editAction($id, Request $request)
    {
        return (new Out())->json( ['ask' => 'wait for a while, please.']);
    }

}
