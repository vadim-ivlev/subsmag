<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Rg\ApiBundle\Entity\Products;
use Rg\ApiBundle\Entity\Kits;
use Rg\ApiBundle\Controller\DataProcessing as Data;
use Rg\ApiBundle\Controller\Outer as Out;
use Rg\ApiBundle\Repository\KitsRepository;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class ProductsController extends Controller
{

    //показать все
    public function indexAction(Request $request)
    {
        $data = new Data();
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $kitRep = new KitsRepository($em, $em->getClassMetadata(get_class(new Products())));

        $products = $em->getRepository('RgApiBundle:Products')->findAll();

        if (!$products) {
            $arrError = [
                'status' => "error",
                'description' => 'Издания не найдены!',
                'code' => 200,
                'id' => null
            ];
            return $out->json($arrError);
        }

        foreach ($products as $key => $product) {
            $productsList[$key]['id'] = $data->dataClearInt($product->getId());
            $productsList[$key]['nameProduct'] = $data->dataClearStr($product->getNameProduct());
            $productsList[$key]['frequency'] = $data->dataClearStr($product->getFrequency());
            $productsList[$key]['flagSubscribe'] = $product->getFlagSubscribe();
            $productsList[$key]['flagBuy'] = $product->getFlagBuy();
            $productsList[$key]['postIndex'] = $data->dataClearInt($product->getPostIndex());
            $productsList[$key]['kits'] = $kitRep->getKitByProductId($product->getId());;
        }

        $response = $out->json($productsList);

        return $response;
    }

    public function showAction($id)
    {
        $data = new Data();
        $out = new Outer();

        $id = $data->dataClearInt($id);
        $em = $this->getDoctrine()->getManager();

        $kitRep = new KitsRepository($em, $em->getClassMetadata(get_class(new Kits())));

        $product = $em->getRepository('RgApiBundle:Products')->findOneBy(['id' => $id]);

        //если пользователь не найден
        if (!$product) {
            $arrError = [
                'status' => "error",
                'description' => 'Издание не найдено!',
                'code' => 200,
                'id' => null
            ];
            $response = $out->json($arrError);
            return $response;
//            throw $this->createNotFoundException('Unable to find post.');
        }

        $productsList['id'] = $data->dataClearStr($product->getId());
        $productsList['nameProduct'] = $data->dataClearStr($product->getNameProduct());
        $productsList['frequency'] = $data->dataClearStr($product->getFrequency());
        $productsList['flagSubscribe'] = $product->getFlagSubscribe();
        $productsList['flagBuy'] = $product->getFlagBuy();
        $productsList['postIndex'] = $data->dataClearInt($product->getPostIndex());

        $kits = $kitRep->getRelationByEntityId($id, 'kit', 'product');
        $productsList['kits'] = $kits;


        //собираем JSON для вывода
        $response = $out->json($productsList);

        return $response;

//        return $this->render('RgApiBundle:Default:index.html.twig');
    }

    public function createAction(Request $request)
    {
        $data = new Data();
        $out = new Outer();

        $arrJSONIn = json_decode($request->getContent(), true, 10);

        //если пришел не JSON или не удалось его декодить
        if (!is_array($arrJSONIn)) {
            $arrError = [
                'status' => "error",
                'description' => "Некорректный запрос в POST!",
                'code' => 200,
                'id' => null
            ];
            $response = $out->json($arrError);
            return $response;
        }

        $caught = false;

        //добавляем запись
        $em = $this->get('doctrine')->getManager();
        $product = new Products();
        $product->setNameProduct($data->dataClearStr($arrJSONIn['nameProduct']));
        $product->setFrequency($data->dataClearStr($arrJSONIn['frequency']));
        $product->setFlagSubscribe($arrJSONIn['flagSubscribe']);
        $product->setFlagBuy($arrJSONIn['flagBuy']);
        $product->setPostIndex($data->dataClearInt($arrJSONIn['postIndex']));
        
        $em->persist($product);
        try {
            $em->flush();
        }
        catch (UniqueConstraintViolationException $e) {
            //собираем JSON для вывода ошибки
            $arrError = [
                'status' => "error",
                'description' => $e->getMessage(),
                'sqlState' => $e->getSQLState(),
                'errorCode' => $e->getErrorCode(),
                'id' => null
            ];
            $response = $out->json($arrError);
            $caught = true;
        }



        if (!$caught) {
            //собираем JSON для вывода, если ошибок нет
            $response = $out->json($product->getId());

            //если указан(-ы) комплект(-ы) - добавляем издание в комплект(-ы)
            if (is_array($arrJSONIn['kits']) && count($arrJSONIn['kits']) > 0) {
                $kitsRep = new KitsRepository($em, $em->getClassMetadata(get_class(new Kits())));
                foreach ($arrJSONIn['kits'] as $kitId) {
                    if ((integer)$kitId > 0) {
                        $kitsRep->addRelationToEntity($product->getId(), $kitId * 1, 'product', 'kit');
                    }
                }
            }
        }

        return $response;
    }

    public function editAction($id, Request $request)
    {
        $data = new Data();
        $out = new Outer();

        $id = $data->dataClearInt($id);

        $arrJSONIn = json_decode($request->getContent(), true, 10);

        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('RgApiBundle:Products')->findOneBy(['id' => $id]);

        $caught = false;

        //если пользователь не найден
        if (!$product) {
            //собираем JSON для вывода ошибки
            $arrError = [
                'status' => "error",
                'description' => 'Издание не найдено!',
                'id' => null
            ];
            $caught = true;
        }

        //если пришел не JSON или не удалось его декодить
        if (!is_array($arrJSONIn)) {
            $arrError = [
                'status' => "error",
                'description' => "Некорректный запрос в PUT!",
                'code' => 200,
                'id' => null
            ];
            $caught = true;
        }

        //обновляем запись
        $em = $this->getDoctrine()->getManager();

        if (isset($arrJSONIn['nameProduct']))
            $productsList['nameProduct'] = $product->setNameProduct($data->dataClearStr($arrJSONIn['nameProduct']));

        if (isset($arrJSONIn['frequency']))
            $productsList['frequency'] = $product->setFrequency($data->dataClearStr($arrJSONIn['frequency']));

        if (isset($arrJSONIn['flagSubscribe']))
            $productsList['flagSubscribe'] = $product->setFlagSubscribe($arrJSONIn['flagSubscribe']);

        if (isset($arrJSONIn['flagBuy']))
            $productsList['flagBuy'] = $product->setFlagBuy($arrJSONIn['flagBuy']);

        if (isset($arrJSONIn['postIndex']))
            $productsList['postIndex'] = $product->setPostIndex($data->dataClearInt($arrJSONIn['postIndex']));

        try {
            $em->flush();
        }
        catch (UniqueConstraintViolationException $e) {
            //собираем JSON для вывода ошибки
            $arrError = [
                'status' => "error",
                'description' => $e->getMessage(),
                'sqlState' => $e->getSQLState(),
                'errorCode' => $e->getErrorCode(),
                'id' => null
            ];
            $caught = true;
        }


        //если какая-то ошибка - выводим ее
        if ($caught) {
            //собираем JSON для вывода, если ошибок нет
            $response = $out->json($arrError);
            return $response;
        }

        //если указан(-ы) комплект(-ы) - добавляем издание в комплект(-ы) [МАССИВ!!!]
        if (is_array($arrJSONIn['kits']) && count($arrJSONIn['kits']) > 0) {
            $kitsRep = new ProductsRepository($em, $em->getClassMetadata(get_class(new Kits())));
            //сначала удаляем все связи изданиЕ-комплектЫ
            $kitsRep->removeProductFromKit($id, 0, true);
            //и создаем новые связи издания с комплектами
            foreach ($arrJSONIn['kits'] as $kitId) {
                if ($kitId * 1 > 0) {
                    $kitsRep->addRelationToEntity($id, $kitId, 'product', 'kit');
                }
            }
        }


        $arr = [print_r($request->getContent(), true)];
        $response = $out->json($arr);

        return $response;
//        return $this->render('RgApiBundle:Default:index.html.twig');
    }

}
