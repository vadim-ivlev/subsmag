<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Rg\ApiBundle\Entity\Subscribes;
use Rg\ApiBundle\Entity\Promocodes;
use Rg\ApiBundle\Entity\Products;
use Rg\ApiBundle\Entity\Kits;
use Rg\ApiBundle\Entity\Areas;
use Rg\ApiBundle\Repository\SubscribesRepository;
use Rg\ApiBundle\Controller\DataProcessing as Data;
use Rg\ApiBundle\Controller\Outer as Out;
use Rg\ApiBundle\Controller\ZonesController;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class SubscribesController extends Controller
{

    //показать все
    public function indexAction(Request $request)
    {
        $data = new Data();
        $out = new Out();
//        $area = new Areas();
//        $periodsController = new PeriodsController();

        $em = $this->getDoctrine()->getManager();

        $subscribes = $em->getRepository('RgApiBundle:Subscribes')->findAll();

        //берем параметры из config.yml
        $periodsList = $this->container->getParameter('periods');

        if (!$subscribes) {
            $arrError = [
                'status' => "error",
                'description' => 'Подписки не найдены!',
                'code' => 200,
                'id' => null
            ];
            header('Access-Control-Allow-Origin: *');
            return $out->json($arrError);
//            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        foreach ($subscribes as $key => $subscribe) {

            $area = $em->getRepository('RgApiBundle:Areas')->findOneBy(['id' => $data->dataClearInt($subscribe->getAreaId())]);

            if ($periodsList[$subscribe->getNamePeriodId()]) {
                $periodName = $periodsList[$subscribe->getNamePeriodId()];
            } else {
                //собираем JSON для вывода ошибки
                $periodName = [
                    'status' => "error",
                    'description' => 'Период не найден!',
                    'id' => null
                ];
            }

            $subscribesList[$key]['id'] = $data->dataClearInt($subscribe->getId());
            $subscribesList[$key]['namePeriod'] = $periodName;
            $subscribesList[$key]['area'] = $data->dataClearStr($area->getNameArea());
            $subscribesList[$key]['subscribePeriodStart'] = date_format($subscribe->getSubscribePeriodStart(), 'Y-m-d H:i:s');
            $subscribesList[$key]['subscribePeriodEnd'] = date_format($subscribe->getSubscribePeriodEnd(), 'Y-m-d H:i:s');

            $product = $em->getRepository('RgApiBundle:Products')->findOneBy(['id' => $subscribe->getProductId()]);
            if($product) {
                $arrProduct['id'] = $data->dataClearStr($product->getId());
                $arrProduct['nameProduct'] = $data->dataClearStr($product->getNameProduct());
                $arrProduct['frequency'] = $data->dataClearStr($product->getFrequency());
                $arrProduct['flagSubscribe'] = $product->getFlagSubscribe();
                $arrProduct['flagBuy'] = $product->getFlagBuy();
                $arrProduct['postIndex'] = $data->dataClearInt($product->getPostIndex());
            } else {$arrProduct = [];}
            $subscribesList[$key]['product'] = $arrProduct;

            $kit = $em->getRepository('RgApiBundle:Kits')->findOneBy(['id' => $subscribe->getKitId()]);
            if ($kit) {
                $arrKit['id'] = $data->dataClearStr($kit->getId());
                $arrKit['nameKit'] = $data->dataClearStr($kit->getNameKit());
                $arrKit['flagSubscribe'] = $kit->getFlagSubscribe();
            } else {$arrKit = [];}
            $subscribesList[$key]['kit'] = $arrKit;

//            $subscribesList[$key]['idProduct'] = $data->dataClearInt($subscribe->getProductId());
            //собираем издания в акции
//            $subscribeRep = new SubscribesRepository($em, $em->getClassMetadata(get_class(new Products())));
//            $subscribesList[$key]['products'] = $subscribeRep->getRelationByEntityId($subscribe->getId(), 'product', 'subscribe');

//            $subscribesList[$key]['kits'] = $data->dataClearInt($subscribe->getKitId());
//            $subscribesList[$key]['idUser'] = $data->dataClearInt($subscribe->getUserId());

            $periods = $em->getRepository('RgApiBundle:Periods')->findBy(['subscribeId' => $subscribesList[$key]['id']]);
            if (!$periods) {
                $subscribesList[$key]['periods'] = [];
            } else {
                foreach ($periods as $keyPC => $period) {
                    $subscribesList[$key]['periods'][$keyPC]['id'] = $data->dataClearInt($period->getId());
                    $subscribesList[$key]['periods'][$keyPC]['monthStart'] = $data->dataClearInt($period->getMonthStart());
                    $subscribesList[$key]['periods'][$keyPC]['yearStart'] = $data->dataClearInt($period->getYearStart());
                    $subscribesList[$key]['periods'][$keyPC]['periodMonths'] = $data->dataClearInt($period->getPeriodMonths());
                    $subscribesList[$key]['periods'][$keyPC]['quantityMonthsStart'] = $data->dataClearInt($period->getQuantityMonthsStart());
                    $subscribesList[$key]['periods'][$keyPC]['quantityMonthsEnd'] = $data->dataClearInt($period->getQuantityMonthsEnd());
                    $subscribesList[$key]['periods'][$keyPC]['subscribeId'] = $data->dataClearInt($period->getSubscribeId());

                }
            }
        }

        $response = $out->json($subscribesList);

        header('Access-Control-Allow-Origin: *');
        return $response;
//        return $this->render('RgApiBundle:Default:index.html.twig');
    }

    public function showAction($id)
    {
        $data = new Data();
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $id = $data->dataClearInt($id);

        $subscribe = $em->getRepository('RgApiBundle:Subscribes')->findOneBy(['id' => $id]);

        //берем параметры из config.yml
        $periodsList = $this->container->getParameter('periods');

        $area = $em->getRepository('RgApiBundle:Areas')->findOneBy(['id' => $data->dataClearInt($subscribe->getAreaId())]);

        if ($periodsList[$subscribe->getNamePeriodId()]) {
            $periodName = $periodsList[$subscribe->getNamePeriodId()];
        } else {
            //собираем JSON для вывода ошибки
            $periodName = [
                'status' => "error",
                'description' => 'Период не найден!',
                'id' => null
            ];
        }


        if (!$subscribe) {
            $arrError = [
                'status' => "error",
                'description' => 'Подписка не найдены!',
                'code' => 200,
                'id' => null
            ];
            header('Access-Control-Allow-Origin: *');
            return $out->json($arrError);
//            throw $this->createNotFoundException('Unable to find Blog post.');
        }

//        foreach ($subscribes as $key => $subscribe) {

            $subscribesList['id'] = $data->dataClearInt($subscribe->getId());
            $subscribesList['namePeriod'] = $periodName;
            $subscribesList['area'] = $data->dataClearStr($area->getNameArea());
            $subscribesList['subscribePeriodStart'] = date_format($subscribe->getSubscribePeriodStart(), 'Y-m-d H:i:s');
            $subscribesList['subscribePeriodEnd'] = date_format($subscribe->getSubscribePeriodEnd(), 'Y-m-d H:i:s');

            $product = $em->getRepository('RgApiBundle:Products')->findOneBy(['id' => $subscribe->getProductId()]);
            if($product) {
                $arrProduct['id'] = $data->dataClearStr($product->getId());
                $arrProduct['nameProduct'] = $data->dataClearStr($product->getNameProduct());
                $arrProduct['frequency'] = $data->dataClearStr($product->getFrequency());
                $arrProduct['flagSubscribe'] = $product->getFlagSubscribe();
                $arrProduct['flagBuy'] = $product->getFlagBuy();
                $arrProduct['postIndex'] = $data->dataClearInt($product->getPostIndex());
            } else {$arrProduct = [];}
            $subscribesList['product'] = $arrProduct;

            $kit = $em->getRepository('RgApiBundle:Kits')->findOneBy(['id' => $subscribe->getKitId()]);
            if ($kit) {
                $arrKit['id'] = $data->dataClearStr($kit->getId());
                $arrKit['nameKit'] = $data->dataClearStr($kit->getNameKit());
                $arrKit['flagSubscribe'] = $kit->getFlagSubscribe();
            } else {$arrKit = [];}
            $subscribesList['kit'] = $arrKit;


//            $subscribesList['periodId'] = $data->dataClearInt($subscribe->getPeriodId());
//            $subscribesList['kitId'] = $data->dataClearInt($subscribe->getKitId());

//            $subscribesList['idProduct'] = $data->dataClearInt($subscribe->getProductId());
            //собираем издания в акции
//            $subscribeRep = new SubscribesRepository($em, $em->getClassMetadata(get_class(new Products())));
//            $subscribesList['products'] = $subscribeRep->getRelationByEntityId($subscribe->getId(), 'product', 'subscribe');

//            $subscribesList['kits'] = $data->dataClearInt($subscribe->getKitId());
//            $subscribesList['idUser'] = $data->dataClearInt($subscribe->getUserId());

            $periods = $em->getRepository('RgApiBundle:Periods')->findBy(['subscribeId' => $subscribesList['id']]);
            if (!$periods) {
                $subscribesList['periods'] = [];
            } else {
                foreach ($periods as $keyPC => $period) {
                    $subscribesList['periods'][$keyPC]['id'] = $data->dataClearInt($period->getId());
                    $subscribesList['periods'][$keyPC]['monthStart'] = $data->dataClearInt($period->getMonthStart());
                    $subscribesList['periods'][$keyPC]['yearStart'] = $data->dataClearInt($period->getYearStart());
                    $subscribesList['periods'][$keyPC]['periodMonths'] = $data->dataClearInt($period->getPeriodMonths());
                    $subscribesList['periods'][$keyPC]['quantityMonthsStart'] = $data->dataClearInt($period->getQuantityMonthsStart());
                    $subscribesList['periods'][$keyPC]['quantityMonthsEnd'] = $data->dataClearInt($period->getQuantityMonthsEnd());
                    $subscribesList['periods'][$keyPC]['subscribeId'] = $data->dataClearInt($period->getSubscribeId());

                }
            }
//        }

        $response = $out->json($subscribesList);

        header('Access-Control-Allow-Origin: *');
        return $response;
//        return $this->render('RgApiBundle:Default:index.html.twig');
    }

    public function createAction(Request $request)
    {
        $data = new Data();
        $out = new Out();

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
            header('Access-Control-Allow-Origin: *');
            return $response;
        }

        $caught = false;

        //добавляем запись
        $em = $this->get('doctrine')->getManager();
        $subscribe = new Subscribes();
        $subscribe->setNamePeriodId($data->dataClearInt($arrJSONIn['namePeriodId']));
        $subscribe->setAreaId($data->dataClearInt($arrJSONIn['areaId']));
        $subscribe->setSubscribePeriodStart(date_create($arrJSONIn['subscribePeriodStart']), 'Y-m-d H:i:s');
        $subscribe->setSubscribePeriodEnd(date_create($arrJSONIn['subscribePeriodEnd']), 'Y-m-d H:i:s');
        (!isset($arrJSONIn['productId'])) ?: $subscribe->setProductId($data->dataClearInt($arrJSONIn['productId']));
        (!isset($arrJSONIn['kitId'])) ?: $subscribe->setKitId($data->dataClearInt($arrJSONIn['kitId']));

        //products и kits добавляются ниже!
//        $subscribe->setProductId($data->dataClearInt($arrJSONIn['productId']));
//        $subscribe->setKitId($data->dataClearInt($arrJSONIn['kitId']));

//        $subscribe->setUserId($data->dataClearInt($arrJSONIn['userId']));

        $em->persist($subscribe);
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

            header('Access-Control-Allow-Origin: *');
            return $response;
        }

        //собираем JSON для вывода, если ошибок нет
        $response = $out->json($subscribe->getId());

        return $response;
    }

    public function editAction($id, Request $request)
    {
        $data = new Data();
        $out = new Out();

        $id = $data->dataClearInt($id);

        $arrJSONIn = json_decode($request->getContent(), true, 10);

        $em = $this->getDoctrine()->getManager();

        $subscribe = $em->getRepository('RgApiBundle:Subscribes')->findOneBy(['id' => $id]);

        $caught = false;

        //если подписка не найдена
        if (!$subscribe) {
            //собираем JSON для вывода ошибки
            $arrError = [
                'status' => "error",
                'description' => 'Подписка не найдена!',
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

        if (isset($arrJSONIn['namePeriodId']))
            $subscribesList['namePeriodId'] = $subscribe->setNamePeriodId($data->dataClearInt($arrJSONIn['namePeriodId']));
        if (isset($arrJSONIn['areaId']))
            $subscribesList['areaId'] = $subscribe->setAreaId($data->dataClearStr($arrJSONIn['areaId']));
        if (isset($arrJSONIn['subscribePeriodStart']))
            $subscribesList['subscribePeriodStart'] = $subscribe->setSubscribePeriodStart(date_create($data->dataClearStr($arrJSONIn['subscribePeriodStart'])), 'Y-m-d H:i:s');
        if (isset($arrJSONIn['subscribePeriodEnd']))
            $subscribesList['subscribePeriodEnd'] = $subscribe->setSubscribePeriodEnd(date_create($data->dataClearStr($arrJSONIn['subscribePeriodEnd'])), 'Y-m-d H:i:s');
        if (isset($arrJSONIn['productId']))
            $subscribesList['productId'] = $subscribe->setProductId($data->dataClearStr($arrJSONIn['productId']));
        if (isset($arrJSONIn['kitId']))
            $subscribesList['kitId'] = $subscribe->setKitId($data->dataClearStr($arrJSONIn['kitId']));

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


        $arr = [print_r($request->getContent(), true)];
        $response = $out->json($arr);

        header('Access-Control-Allow-Origin: *');
        return $response;
//        return $this->render('RgApiBundle:Default:index.html.twig');
    }

}
