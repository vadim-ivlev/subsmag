<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Entity\Edition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\DataProcessing as Data;
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
                'description' => 'Издание не найдено.',
                'code' => 200,
                'id' => null
            ];
            return $out->json($arrError);
        }

        $eds = array_map([$this, 'convertToArray'], $editions);

        $response = $out->json((object) $eds);

        return $response;
    }

    private function convertToArray(Edition $edition) {
            return [
                'id' => $edition->getId(),
                'name' => $edition->getName(),
                'keyword' => $edition->getKeyword(),
                'frequency' => $edition->getFrequency(),
                'image' => $edition->getImage(),
            ];
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
                'code' => 200,
                'id' => null
            ];
            return $out->json($arrError);
        }

        $ed = $this->convertToArray($edition);

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
