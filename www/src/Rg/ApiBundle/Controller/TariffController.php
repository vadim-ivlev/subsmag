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

        $prods = array_map([$this, 'convertToArray'], $tariffs);

        $response = $out->json((object) $prods)
            ->setStatusCode(Response::HTTP_NOT_FOUND);

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

        $response = $out->json((object) $this->convertToArray($tariff));

        return $response;
    }

    private function convertToArray(Tariff $tariff) {
        return [
            'id' => $tariff->getId(),
            'product_id' => $tariff->getProduct()->getId(),
            'period_id' => $tariff->getPeriod()->getId(),
            'delivery_id' => $tariff->getDelivery()->getId(),
            'zone_id' => $tariff->getZone()->getId(),
            'price' => $tariff->getPrice(),
        ];
    }

    public function filterByProductIdAction($product_id)
    {
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $tariffs = $em->getRepository('RgApiBundle:Tariff')->findBy(['product' => $product_id]);

        if (!$tariffs) {
            $arrError = [
                'status' => "error",
                'description' => 'Тарифы не найдены!',
                'code' => Response::HTTP_NOT_FOUND,
            ];
            return $out->json($arrError);
        }

        $prods = array_map([$this, 'convertToArray'], $tariffs);

        $response = $out->json((object) $prods)
            ->setStatusCode(Response::HTTP_NOT_FOUND);

        return $response;
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
