<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;

/**
 * Class PromoRequestController
 * @package Rg\ApiBundle\Controller
 */
class ContentController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function listAction(Request $request)
    {
        $c = $this->getDoctrine()->getRepository('RgApiBundle:Content')
            ->getSingle()
        ;

        $out = [
            'id' => $c->getId(),
            'departments' => json_decode($c->getDepartments()),
            'contacts' => json_decode($c->getContacts()),
            'support' => json_decode($c->getSupport()),
            'subscribe' => json_decode($c->getSubscribe()),
            'auxiliary' => json_decode($c->getAuxiliary()),
        ];

        return (new Out())->json($out);
    }
}
