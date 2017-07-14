<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Entity\Edition;
use Rg\ApiBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\DataProcessing as Data;
use Rg\ApiBundle\Controller\Outer as Out;

class ProductController extends Controller
{

    public function indexAction()
    {
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('RgApiBundle:Product')->findAll();

        if (!$products) {
            $arrError = [
                'status' => "error",
                'description' => 'Комплекты не найдены!',
                'code' => 200,
                'id' => null
            ];
            return $out->json($arrError);
        }

        $prods = array_map([$this, 'getEditionsAndConvertToArray'], $products);

        $response = $out->json((object) $prods);

        return $response;
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
                'code' => 200,
                'id' => null
            ];
            return $out->json($arrError);
        }

        $prod = $this->getEditionsAndConvertToArray($product);

        $response = $out->json((object) $prod);

        return $response;
    }

    private function getEditionsAndConvertToArray(Product $product) {
        $editions = array_map(function (Edition $edition) {
            return [
                'id' => $edition->getId(),
                'name' => $edition->getName(),
                'keyword' => $edition->getKeyword(),
                'frequency' => $edition->getFrequency(),
                'image' => $edition->getImage(),
            ];
        }, iterator_to_array($product->getEditions()));

        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'postal_index' => $product->getPostalIndex(),
            'sort' => $product->getSort(),
            'editions' => $editions,
        ];
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
