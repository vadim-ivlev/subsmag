<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Rg\ApiBundle\Entity\Kits as Kits;
use Rg\ApiBundle\Repository\KitsRepository;
use Rg\ApiBundle\Entity\Products as Products;
use Rg\ApiBundle\Entity\RawDB as RawDB;
use Rg\ApiBundle\Controller\DataProcessing as Data;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Tests\Fixtures\Entity;

class KitsController extends Controller
{

    //показать все
    public function indexAction(Request $request)
    {
        $data = new Data();
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $kits = $em->getRepository('RgApiBundle:Kits')->findAll();

        if (!$kits) {
            $arrError = [
                'status' => "error",
                'description' => 'Комплекты не найдены!',
                'code' => 200,
                'id' => null
            ];
            return $out->json($arrError);
        }

        //собираем издания в комплекте
        $kitRep = new KitsRepository($em, $em->getClassMetadata(get_class(new Products())));

        foreach ($kits as $key => $kit) {
            $arrProducts = $kitRep->getRelationByEntityId($kit->getId(), 'product', 'kit');
            $kitsList[$key]['id'] = $data->dataClearInt($kit->getId());
            $kitsList[$key]['nameKit'] = $data->dataClearStr($kit->getNameKit());
            $kitsList[$key]['flagSubscribe'] = $kit->getFlagSubscribe();
            $kitsList[$key]['products'] = $arrProducts;
        }

//        $customer = $em->getRepository( "QR1000MainBundle:Customer" )->findOneById( $customerID );


        $response = $out->json($kitsList);

        return $response;
    }

    public function showAction($id)
    {
        $data = new Data();
        $out = new Outer();

        $id = $data->dataClearInt($id);
        $em = $this->getDoctrine()->getManager();

        $kit = $em->getRepository('RgApiBundle:Kits')->findOneBy(['id' => $id]);

        //если пользователь не найден
        if (!$kit) {
            $arrError = [
                'status' => "error",
                'description' => 'Комплект не найден!',
                'code' => 200,
                'id' => null
            ];
            $response = $out->json($arrError);
            return $response;
//            throw $this->createNotFoundException('Unable to find post.');
        }

        //собираем издания в комплекте
        $kitRep = new KitsRepository($em, $em->getClassMetadata(get_class(new Products())));
        $arrProducts = $kitRep->getRelationByEntityId($id, 'product', 'kit');

        //формируем ответ
        $kitsList['id'] = $data->dataClearStr($kit->getId());
        $kitsList['nameKit'] = $data->dataClearStr($kit->getNameKit());
        $kitsList['flagSubscribe'] = $kit->getFlagSubscribe();
        $kitsList['products'] = $arrProducts;

        //собираем JSON для вывода
        $response = $out->json($kitsList);

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
        $kit = new Kits();
        $kit->setNameKit($data->dataClearStr($arrJSONIn['nameKit']));
        $kit->setFlagSubscribe($arrJSONIn['flagSubscribe']);

        $em->persist($kit);
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
            $response = $out->json($kit->getId());

            //если указан(-ы) издания(-ы) - добавляем издание(-я) в комплект
            if (is_array($arrJSONIn['products']) && is_array($arrJSONIn['products']) && count($arrJSONIn['products']) > 0) {
                $kitRep = new KitsRepository($em, $em->getClassMetadata(get_class(new Products())));
                foreach ($arrJSONIn['products'] as $productId) {
                    if ($productId * 1 > 0) {
                        $kitRep->addRelationToEntity($productId, $kit->getId(), 'product', 'kit');
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

        $kit = $em->getRepository('RgApiBundle:Kits')->findOneBy(['id' => $id]);

        $caught = false;

        //если пользователь не найден
        if (!$kit) {
            //собираем JSON для вывода ошибки
            $arrError = [
                'status' => "error",
                'description' => 'Комплект не найден!',
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

        if (isset($arrJSONIn['nameKit']))
            $kitsList['nameKit'] = $kit->setNameKit($data->dataClearStr($arrJSONIn['nameKit']));

        if (isset($arrJSONIn['flagSubscribe']))
            $kitsList['flagSubscribe'] = $kit->setFlagSubscribe($arrJSONIn['flagSubscribe']);

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


        //если указан(-ы) товар(-ы) - добавляем товар в комплект(-ы) [МАССИВ!!!]
        if (isset($arrJSONIn['products']) && is_array($arrJSONIn['products']) && count($arrJSONIn['products']) > 0) {
            $kitsRep = new KitsRepository($em, $em->getClassMetadata(get_class(new Kits())));
            //сначала удаляем все связи изданиЕ-акциИ
            $kitsRep->removeRelationFromEntity(0, $id, 'product', 'kit', true);
            //и создаем новые связи издания с комплектами
            foreach ($arrJSONIn['products'] as $productId) {
                if ($productId * 1 > 0) {
                    $kitsRep->addRelationToEntity($productId, $id, 'product', 'kit');
                }
            }
        }

        $arr = [print_r($request->getContent(), true)];
        $response = $out->json($arr);

        return $response;
//        return $this->render('RgApiBundle:Default:index.html.twig');
    }

}
