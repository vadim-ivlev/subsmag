<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Entity\Tariff;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\HttpFoundation\Response;

class TariffController extends Controller
{

    public function indexAction()
    {
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $tariffs = $em->getRepository('RgApiBundle:Tariff')->findAll();

        if (!$tariffs) {
            $arrError = [
                'status' => "error",
                'description' => 'Тарифы не найдены!',
                'code' => Response::HTTP_NOT_FOUND,
            ];
            return $out->json($arrError);
        }

        $normalized = array_map([$this, 'convertToArray'], $tariffs);

        $response = $out->json($normalized)
            ->setStatusCode(Response::HTTP_OK);

        return $response;
    }

    public function showAction($id)
    {
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $tariff = $em->getRepository('RgApiBundle:Tariff')->find($id);

        if (!$tariff) {
            $arrError = [
                'status' => "error",
                'description' => 'Тариф не найден!',
                'code' => Response::HTTP_NOT_FOUND,
            ];
            return $out->json($arrError);
        }

        $response = $out->json($this->convertToArray($tariff));

        return $response;
    }

    private function convertToArray(Tariff $tariff) {
        $product = $tariff->getProduct();
        $period = $tariff->getPeriod();
        $delivery = $tariff->getDelivery();
        $zone = $tariff->getZone();
        $media = $tariff->getMedium();

        return [
            'id' => $tariff->getId(),
            'product' => [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
            ],
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
            'zone' => [
                'id' => $zone->getId(),
                'name' => $zone->getName(),
            ],
            'media' => [
                'id' => $media->getId(),
                'name' => $media->getName(),
                'alias' => $media->getAlias(),
            ],
            'price' => $tariff->getPrice(),
        ];
    }

    public function filterByProductIdAction($product_id)
    {
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $tariffs = $em->getRepository('RgApiBundle:Tariff')->filterByProductId($product_id);

        if (!$tariffs) {
            $arrError = [
                'status' => "error",
                'description' => 'Тарифы не найдены!',
                'code' => Response::HTTP_NOT_FOUND,
            ];
            return $out->json($arrError);
        }

        $normalized = array_map([$this, 'convertToArray'], $tariffs);

        $response = $out->json( $normalized);

        return $response;
    }

    public function createAction(Request $request)
    {
        return (new Out())->json(['ask' => 'wait for a while, please.']);
    }

    public function editAction($id, Request $request)
    {
        return (new Out())->json( ['ask' => 'wait for a while, please.']);
    }

}
