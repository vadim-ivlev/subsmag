<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;

class EditionController extends Controller
{

    //показать все
    public function indexAction()
    {
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $editions = $em->getRepository('RgApiBundle:Edition')->findAll();

        if (!$editions) {
            $arrError = [
                'status' => "error",
                'description' => 'Изданий не найдено.',
            ];
            return $out->json($arrError);
        }

        $eds = array_map([$this->get('rg_api.edition_normalizer'), 'convertToArray'], $editions);

        $response = $out->json((object) $eds);

        return $response;
    }

    public function showAction($id)
    {
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $edition = $em->getRepository('RgApiBundle:Edition')->find($id);

        if (!$edition) {
            $arrError = [
                'status' => "error",
                'description' => 'Издание не найдено.',
            ];
            return $out->json($arrError);
        }

        $ed = $this->get('rg_api.edition_normalizer')->convertToArray($edition);

        $response = $out->json((object) $ed);

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
