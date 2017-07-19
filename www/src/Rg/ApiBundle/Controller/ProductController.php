<?php

namespace Rg\ApiBundle\Controller;

use Rg\ApiBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\HttpFoundation\Response;

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
            ];
            return $out->json($arrError);
        }

        $prod = $this->getEditionsAndConvertToArray($product);

        $response = $out->json((object) $prod);

        return $response;
    }

    private function getEditionsAndConvertToArray(Product $product) {
        $editions = array_map(
            [$this->get('rg_api.edition_normalizer'), 'convertToArray'],
            iterator_to_array($product->getEditions())
        );

        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'postal_index' => $product->getPostalIndex(),
            'is_kit' => $product->getIsKit(),
            'is_archive' => $product->getIsArchive(),
            'is_outer' => $product->getIsOuter(),
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
