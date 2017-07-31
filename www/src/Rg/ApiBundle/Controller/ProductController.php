<?php

namespace Rg\ApiBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Rg\ApiBundle\Entity\Delivery;
use Rg\ApiBundle\Entity\Edition;
use Rg\ApiBundle\Entity\Good;
use Rg\ApiBundle\Entity\Product;
use Rg\ApiBundle\Entity\Tariff;
use Rg\ApiBundle\Entity\Timeblock;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;

class ProductController extends Controller
{
    const MOSCOW = 3;

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

        ### attach available periods for a product with specified media and deliveries
        $container_with_periods = array_map(
            function (array $item) use ($area) {
                /** @var Product $product */
                $product = $item[0];

                $item['media'] = array_map(
                    function (array $medium) use ($product, $area) {

                        if (empty($medium['deliveries'])) return $medium;

                        $medium['deliveries'] = array_map(
                            function (array $delivery) use ($product, $area, $medium) {
                                $goods = $product->getGoods();

                                ############
                                /**
                                 * есть регионализированные периоды, есть общие.
                                 * если есть period-area для этого региона, взять его вместо общего.
                                 */
                                ############
                                $partitioned = $goods->partition(
                                    function ($key, Good $good) {
                                        return !($good->getIsRegional());
                                    }
                                );

                                /** @var ArrayCollection $for_all_regions_goods */
                                $for_all_regions_goods = $partitioned[0];
                                /** @var ArrayCollection $region_specific_goods */
                                $region_specific_goods = $partitioned[1];

                                $area_checked = $region_specific_goods->filter(
                                    function (Good $good) use ($area) {
                                        $area_criterion = $good->getArea()->getFromFrontId() == $area->getFromFrontId();

                                        return $area_criterion;
                                    }
                                );

                                $filtered_by_area_goods = $for_all_regions_goods->map(
                                    function (Good $good) use ($area_checked) {

                                        foreach ($area_checked->getIterator() as $replacer) {
                                            if ($good->getPeriod()->getId() == $replacer->getPeriod()->getId()) {
                                                return $replacer;
                                            }
                                        }

                                        return $good;
                                    }
                                );
                                ############
                                ############

                                $filtered_by_date_and_area_goods = $filtered_by_area_goods->filter(
                                    function (Good $good) {
                                        $start = $good->getStart();
                                        $end = $good->getEnd();

                                        $criterion = ((new \DateTime()) > $start) and ((new \DateTime()) < $end);

                                        return $criterion;
                                    }
                                );

                                $normalized_periods = $filtered_by_date_and_area_goods->map(
                                    function (Good $good) {
                                        $period = $good->getPeriod();

                                        return [
                                            'id' => $period->getId(),
                                            'first_month' => $period->getFirstMonth(),
                                            'duration' => $period->getDuration(),
                                            'year' => $period->getYear(),
                                        ];
                                    }
                                );

                                #######
                                /**
                                 * cost for goods-tariffs
                                 */
                                #######
                                $tariffied_periods = $normalized_periods->map(
                                    function (array $period) use ($product, $medium, $delivery, $area) {


                                        $bitmask = $this->get('rg_api.period_timeunit_converter')
                                            ->convertPeriodStartDurationToTimeunitMask(
                                                $period['first_month'],
                                                $period['duration']
                                            );

                                        $tariff_rep = $this->getDoctrine()->getRepository('RgApiBundle:Tariff');

                                        $price = $tariff_rep->getPriceByProductMediumDeliveryPeriodAreaTimeunit(
                                            $product,
                                            $medium['id'],
                                            $delivery['id'],
                                            $area,
                                            $bitmask
                                        );


                                        if ($period['duration'] == 6 or $period['duration'] == 12)
                                            $cost = $price;
                                        else
                                            $cost = $price * $period['duration'];

                                        $period['cost'] = (float) $cost;

                                        return $period;
                                    }
                                );
                                #######
                                #######

                                $periods = array_values($tariffied_periods->toArray());

                                $delivery['periods'] = $periods;

                                return $delivery;
                            },
                            $medium['deliveries']
                        );

                        return $medium;
                    },
                    $item['media']
                );

                return $item;
            },
            $container_with_deliveries
        );


        $show = $container_with_periods;

//        dump($show);die;

        return  $out->json($show);
    }

    private function normalizeEditions(Edition $edition) {
        return [
            'id' => $edition->getId(),
            'name' => $edition->getName(),
            'keyword' => $edition->getKeyword(),
            'description' => $edition->getDescription(),
            'frequency' => $edition->getFrequency(),
            'image' => $edition->getImage(),
        ];
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
