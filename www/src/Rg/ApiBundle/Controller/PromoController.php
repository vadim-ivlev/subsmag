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
                ];
            },
            $promos
        );

        return (new Out())->json($prepared);
    }
}
