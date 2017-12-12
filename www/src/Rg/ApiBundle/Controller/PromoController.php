<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Entity\Promo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;

/**
 * Class PromoRequestController
 * @package Rg\ApiBundle\Controller
 */
class PromoController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function listAction(Request $request)
    {
        $promos = $this->getDoctrine()->getRepository('RgApiBundle:Promo')
            ->findAllActive()
        ;

        $prepared = array_map(
            function (Promo $p) {
                return [
                    'name' => $p->getName(),
//                    'start' => $p->getStart()->format('dd-mm-YY'),
//                    'end' => $p->getEnd()->format('dd-mm-YY'),
                    'is_active' => $p->getIsActive(),
                    'code' => $p->getCode(),
                    'discount' => $p->getDiscount(),
                    'amount' => $p->getAmount(),
                    'sold' => $p->getSold(),
                    'description' => $p->getDescription(),
                    'image' => $p->getImage(),
                    'is_alert' => $p->getIsAlert(),
                    'is_visible' => $p->getIsVisible(),
                    'is_form' => $p->getIsForm(),
                    'document' => $p->getDocument(),
                    'title' => $p->getTitle(),
                    'text2' => $p->getText2(),
                    'text3' => $p->getText3(),
                    'conditions' => $p->getConditions(),

                    'alias' => urlencode($p->getCode()),
                ];
            },
            $promos
        );

        return (new Out())->json($prepared);
    }

    public function getOneByIdAction(Request $request, int $id)
    {
        if (!$this->validateId($id)) {
            $error = 'invalid id';
            return (new Out())->json(['error' => $error]);
        }

        $p = $this->getDoctrine()->getRepository('RgApiBundle:Promo')
            ->findOneBy(['id' => $id])
        ;
        if (is_null($p)) {
            return (new Out())->json(['error' => 'Акция не найдена или неактивна.']);
        }

        $prepared = [
            'name' => $p->getName(),
//                    'start' => $p->getStart()->format('dd-mm-YY'),
//                    'end' => $p->getEnd()->format('dd-mm-YY'),
            'is_active' => $p->getIsActive(),
            'code' => $p->getCode(),
            'discount' => $p->getDiscount(),
            'amount' => $p->getAmount(),
            'sold' => $p->getSold(),
            'description' => $p->getDescription(),
            'image' => $p->getImage(),
            'is_alert' => $p->getIsAlert(),
            'is_visible' => $p->getIsVisible(),
            'is_form' => $p->getIsForm(),
            'document' => $p->getDocument(),
            'title' => $p->getTitle(),
            'text2' => $p->getText2(),
            'text3' => $p->getText3(),
            'conditions' => $p->getConditions(),

            'alias' => urlencode($p->getCode()),
        ];

        return (new Out())->json($prepared);
    }

    private function validateId($id)
    {
        $options = [
            'options' => [
                'min_range' => 1
            ],
        ];

        return filter_var($id, FILTER_VALIDATE_INT, $options);
    }
}
