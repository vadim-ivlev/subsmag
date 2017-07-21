<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Entity\Edition;
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

        $from_front_id = $request->query->get('zone_id', $this::MOSCOW);

        $products = $em->getRepository('RgApiBundle:Product')
            ->getProductsWithMinPricesByArea($from_front_id);

        if (!$products) {
            $arrError = [
                'status' => "error",
                'description' => 'Комплекты не найдены!',
            ];
            return $out->json($arrError);
        }

        $prods = array_map([$this, 'getEditionsAndTariffs'], $products);

        return  $out->json($prods);
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

    /**
     * @param array $product_container
     * @return array
     */
    private function getEditionsAndTariffs(array $product_container) {

        $editions = array_map(
            function (Edition $edition) {
                return [
                    'id' => $edition->getId(),
                    'name' => $edition->getName(),
                    'keyword' => $edition->getKeyword(),
                    'description' => $edition->getDescription(),
                    'text' => $edition->getText(),
                    'frequency' => $edition->getFrequency(),
                    'image' => $edition->getImage(),
                ];
            },
            iterator_to_array($product_container[0]->getEditions())
        );

        $tariffs_arr = iterator_to_array($product_container[0]->getTariffs());

        $tariffs = array_map(
            function (Tariff $tariff) {
                $period = $tariff->getPeriod();
                $delivery = $tariff->getDelivery();
                $media = $tariff->getMedia();

                return [
                    'id' => $tariff->getId(),
                    'period' => [
                        'id' => $period->getId(),
                        'month_start' => $period->getMonthStart(),
                        'year_start' => $period->getYearStart(),
                        'duration' => $period->getDuration(),
                    ],
                    'delivery' => [
                        'id' => $delivery->getId(),
                        'name' => $delivery->getName(),
                        'description' => $delivery->getDescription(),
                    ],
                    'media' => [
                        'id' => $media->getId(),
                        'name' => $media->getName(),
                        'alias' => $media->getAlias(),
                    ],
                    'price' => $tariff->getPrice(),
                ];
            },
            $tariffs_arr
        );

        $product_container['editions'] = $editions;

        $product_container['tariffs'] = $tariffs;

        unset($product_container[0]);

        return $product_container;
    }

    public function createAction(Request $request)
    {
        return (new Out())->json((object) ['ask' => 'wait for a while, please.']);
    }

    public function editAction($id, Request $request)
    {
        return (new Out())->json((object) ['ask' => 'wait for a while, please.']);
    }

}
