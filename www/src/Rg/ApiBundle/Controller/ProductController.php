<?php

namespace Rg\ApiBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Rg\ApiBundle\Entity\Area;
use Rg\ApiBundle\Entity\Edition;
use Rg\ApiBundle\Entity\Product;
use Rg\ApiBundle\Entity\Sale;
use Rg\ApiBundle\Entity\Tariff;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;

class ProductController extends Controller
{
    /** @var  \DateTime */
    private $current_time;

    public function indexAction(Request $request)
    {
        $this->current_time = new \DateTime();

        //TODO: test!! Remove it.
        ### test!!!
//        $this->current_time = new \DateTime('2018-03-01');
        ### test!!!

        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $from_front_id = (int) $request->query->get('area_id', $this->getParameter('area'));

        $area = $em->getRepository('RgApiBundle:Area')->findOneBy(['from_front_id' => $from_front_id]);

        if (!$area) {
            $arrError = [
                'status' => "error",
                'description' => 'Регион не найден.',
            ];
            return $out->json($arrError);
        }

        $product_container = $em->getRepository('RgApiBundle:Product')
            ->getActiveProductsWithMinPricesByArea($from_front_id);

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
                            'text' => $edition->getText(),
                            'texta' => $edition->getTexta(),
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
                                    'sort' => $delivery->getSort(),
                                ]);
                            }
                        );

                        $unique_delivs = array_map(
                            function($item) { return unserialize($item); },
                            array_values(array_unique($all_deliveries->toArray()))
                        );
                        usort($unique_delivs, function($a, $b) { return $a['sort'] <=> $b['sort']; });

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
                            $this->appendDeliveries($product, $area, $medium),
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


        ### attach minimal prices
        $container_with_min_prices = array_map(
            function (array $item) use ($area) {
                /** @var Product $product */
                $product = $item[0];

                $tariffs = $product->getTariffs();

                $filtered_tariffs = $tariffs->filter(
                    function (Tariff $tariff) use ($area) {
                        $price = $tariff->getCataloguePrice() + $tariff->getDeliveryPrice();
                        if ($price == 0) return false;

                        $criterion = ($tariff->getZone()->getId() == $area->getZone()->getId());

                        // есть два типа тарифов -- месячные и {полугодовые-годовые}
                        // год тарифа д.б. не меньше текущего года.
                        // первый месяц тарифа, если не нулевой (это месячный), д.б. не меньше текущего месяца.
                        /** @var \DateTime $time */
                        $time = $this->current_time;
                        $current_month = (int) $time->format('m');
                        $current_year = (int) $time->format('Y');

                        // если больший год, то месяца/полугодия не фильтруем
                        $tariff_year = (int) $tariff->getTimeunit()->getYear();
                        if ( $tariff_year < $current_year ) return false;
                        if ( $tariff_year > $current_year ) return $criterion;

                        // если текущий год, то полугодие месячного/полугодового тарифа д.б. не меньше полугодия текущего месяца.
                        $current_semiyear = ($current_month < 7) ? 1 : 2;
                        $tariff_first_month = (int) $tariff->getTimeunit()->getFirstMonth();
                        $tariff_semiyear = ($tariff_first_month < 7) ? 1 : 2;

                        if ($tariff_semiyear < $current_semiyear) return false;

                        return $criterion;
                    }
                );

                if ($filtered_tariffs->count() == 0) {
                    $item['min_price'] = null;

                    return $item;
                }

                $prices = $filtered_tariffs->map(
                    function (Tariff $tariff) {
                        return ($tariff->getCataloguePrice() + $tariff->getDeliveryPrice());
                    }
                )
                    ->toArray();

                $item['min_price'] = min($prices);

                return $item;
            },
            $container_with_periods
        );

        $cleaned_from_Doctrine_element = $this->unsetLuggage($container_with_min_prices);

        return  $out->json($cleaned_from_Doctrine_element);
    }

    private function unsetLuggage(array $container)
    {
        return array_map(
            function (array $item) {
                ### clean luggage
                unset($item[0]);

                return $item;
            },
            $container
        );
    }

    private function appendPrices(Collection $tariff_collection): Collection
    {
        return $tariff_collection->map(
            function (Tariff $tariff) {
                $timeunit = $tariff->getTimeunit();
                $price = ($tariff->getCataloguePrice() + $tariff->getDeliveryPrice());
                $id = $tariff->getId();

                // скидка рассчитывается на фронте!
//                $discount = $tariff->getDiscount() ?? 0;

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

//                    'discount' => $discount,
                ];
                return $norm;
            }
        );
    }

    private function filterTariffs(
        Collection $tariffs,
        $medium,
        $delivery,
        Area $area,
        array $sales
    ): Collection
    {
        // нет окна продаж?
        if (empty($sales)) {
            $tariffs->clear();
            return $tariffs;
        }
        if (count($sales) > 2) {
            //возможно ли, что окон продаж больше двух?
            $tariffs->clear();
            return $tariffs;
        }

        $filtered_by_today = $tariffs->filter(
            function (Tariff $tariff) use ($medium, $delivery, $area) {
                if ($tariff->getMedium()->getId() != $medium['id']) return false;
                if ($tariff->getDelivery()->getId() != $delivery['id']) return false;
                if ($tariff->getZone()->getId() != $area->getZone()->getId()) return false;

                /** @var \DateTime $time */
                $time = $this->current_time;

                $tariff_year = (int) $tariff->getTimeunit()->getYear();

                $current_year = (int) $time->format('Y');
                if ( $tariff_year < $current_year ) return false;

                $tariff_first_month = (int) $tariff->getTimeunit()->getFirstMonth();

                if ( $tariff_year > $current_year ) return true;

                // если текущий год, то полугодие месячного/полугодового тарифа д.б. не меньше полугодия текущего месяца.
                $current_month = (int) $time->format('m');
                $current_semiyear = ($current_month < 7) ? 1 : 2;
                $tariff_semiyear = ($tariff_first_month < 7) ? 1 : 2;

                if ($tariff_semiyear < $current_semiyear) return false;

                return true;
            }
        );

        // каждому окну продаж -- тарифы его года
        $mapped = array_map(
            function (array $sale) use ($filtered_by_today) {
                return $filtered_by_today->filter(
                    function (Tariff $tariff) use ($sale) {
                        $t_year = $tariff->getTimeunit()->getYear();
                        if ($t_year != $sale['year']) return false;

                        /** @var \DateTime $time */
                        $time = $this->current_time;
                        $current_year = (int) $time->format('Y');
                        $tariff_first_month = (int) $tariff->getTimeunit()->getFirstMonth();
                        $duration = $tariff->getTimeunit()->getDuration();

                        if ($t_year == $current_year) {
                            if ($duration != 1) {
                                // годовой-полугодовой
                                if ($tariff_first_month < $sale['number']) return false;
                            }
                        } else {
                            if ($duration != 1) {
                                // первый месяц немесячного тарифа д.б. не меньше месяца доступного сейлза
                                if ($tariff_first_month < $sale['number']) return false;
                            } else {
                                $sale_semiyear = ($sale['number'] < 7) ? 1 : 2;
                                $tariff_semiyear = ($tariff_first_month < 7) ? 1 : 2;

                                if ($tariff_semiyear < $sale_semiyear) return false;
                            }
                        }


                        return true;
                    }
                );
            },
            $sales
        );

        if (count($mapped) == 1) {
            return $mapped[0];
        }

        $reduced = array_reduce(
            $mapped,
            function (ArrayCollection $init, ArrayCollection $col) {
                foreach ($col as $c) {
                    $init->add($c);
                }
                return $init;
            },
            $init = new ArrayCollection()
        );
//        echo $this->get('rg_api.dev_dumper')->symDump($reduced->map(function(Tariff $t){
//            return $t->getTimeunit()->getFirstMonth() . ':' . $t->getTimeunit()->getDuration() . ':' . $t->getTimeunit()->getYear();
//        }));die;
//        echo $this->get('rg_api.dev_dumper')->symDump($reduced);die;

        return $reduced;
    }

    private function fetchFilteredSales(Product $product, Area $area, int $delivery_id)
    {
        $sales = $product->getSales()->filter(
            function (Sale $sale) use ($delivery_id) {
                return $sale->getDelivery()->getId() == $delivery_id;
            }
        );

        ############
        /**
         * есть регионализированные продажи, есть общие.
         * если есть month-area для этого региона, взять его вместо общего.
         * Сейчас логика полагает, что обязательно есть федеральные окна продаж,
         * и для каждого федерального могут быть или не быть региональные.
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
                $area_criterion = ($sale->getArea()->getFromFrontId() == $area->getFromFrontId());

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

                ###
                $date = new \DateTime(date('Y-m-d'));
                ###
                $criterion = (($date >= $start) && ($date <= $end));

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

        return array_values($normalized_sales->toArray());
    }

    private function appendDeliveries(Product $product, $area, $medium)
    {
        return function (array $delivery) use ($product, $area, $medium) {
            $filteredSales = $this->fetchFilteredSales($product, $area, $delivery['id']);
            $delivery['sales'] = $filteredSales;

            #######
            /**
             * timeunits and prices
             */
            #######
            $tariffs = $product->getTariffs();

            $filtered_tariffs = $this->filterTariffs(
                $tariffs,
                $medium,
                $delivery,
                $area,
                $filteredSales
            );

            $timeunits_with_prices = $this->appendPrices($filtered_tariffs);

            $tars = array_values($timeunits_with_prices->toArray());

            // сортируем по возрастанию в порядке, указанном в fields
            $fields = ['year', 'duration', 'first_month'];

            usort(
                $tars,
                function($a, $b) use ($fields) {
                    $i = 0;
                    $c = count($fields);
                    $cmp = 0;

                    while ($cmp == 0 && $i < $c) {
                        $cmp = $a['timeunit'][$fields[$i]] <=> $b['timeunit'][$fields[$i]];
                        $i++;
                    }

                    return $cmp;
                }
            );

            $delivery['tariffs'] = $tars;
            #######
            #######

            return $delivery;
        };
    }

    private function DEPRECATED_filterTariffsByCurrentDate(Collection $tariffs, $product, $medium, $delivery, Area $area, array $sales)
    {
        return $tariffs->filter(
            function (Tariff $tariff) use ($product, $medium, $delivery, $area, $sales) {
                if ($tariff->getMedium()->getId() != $medium['id']) return false;
                if ($tariff->getDelivery()->getId() != $delivery['id']) return false;
                if ($tariff->getZone()->getId() != $area->getZone()->getId()) return false;


                // есть два типа тарифов -- месячные и {полугодовые-годовые}
                // год тарифа д.б. не меньше текущего года.
                // первый месяц тарифа, если не нулевой (это месячный), д.б. не меньше текущего месяца.
                /** @var \DateTime $time */
                $time = $this->current_time;
                $current_month = (int) $time->format('m');
                $current_year = (int) $time->format('Y');

                // если больший год, то месяца не фильтруем
                $tariff_year = (int)$tariff->getTimeunit()->getYear();
                if ( $tariff_year < $current_year ) return false;
                if ( $tariff_year > $current_year ) return true;

                // если текущий год, то полугодие месячного/полугодового тарифа д.б. не меньше полугодия текущего месяца.
                $current_semiyear = ($current_month < 7) ? 1 : 2;
                $tariff_first_month = (int) $tariff->getTimeunit()->getFirstMonth();
                $tariff_semiyear = ($tariff_first_month < 7) ? 1 : 2;

                if ($tariff_semiyear < $current_semiyear) return false;

                return true;
            }
        );
    }
}
