<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Rg\ApiBundle\Entity\Orders as Orders;
use Rg\ApiBundle\Entity\Promocodes as Promocodes;
use Rg\ApiBundle\Controller\DataProcessing as Data;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class OrdersController extends Controller
{

    //показать все
    public function indexAction(Request $request)
    {
        $out = new Out();

        //берем параметры из config.yml
        $ordersList = $this->container->getParameter('orders');

        $response = $out->json($ordersList);

        header('Access-Control-Allow-Origin: *');
        return $response;
    }

    public function showAction($id)
    {
        $data = new Data();
        $out = new Out();

        $id = $data->dataClearInt($id);

        //берем параметры из config.yml
        $ordersList = $this->container->getParameter('orders');

        if ($ordersList[$id]) {
            $arrOut = [$id => $ordersList[$id]];
        } else {
            //собираем JSON для вывода ошибки
            $arrOut = [
                'status' => "error",
                'description' => 'Период не найден!',
                'id' => null
            ];
        }

        //собираем JSON для вывода
        $response = $out->json($arrOut);

        header('Access-Control-Allow-Origin: *');
        return $response;
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
        $order = new Orders();
        $order->setUserId($data->dataClearInt($arrJSONIn['userId']));
        $order->setZoneId($data->dataClearInt($arrJSONIn['zoneId']));
        $order->setProductId($data->dataClearInt($arrJSONIn['productId']));
        $order->setKitId($data->dataClearInt($arrJSONIn['kitId']));
        $order->setSubscribeId($data->dataClearInt($arrJSONIn['subscribeId']));
        $order->setDate(date_create($arrJSONIn['date']), 'Y-m-d H:i:s');
        $order->setAddress($data->dataClearInt($arrJSONIn['address']));
        $order->setPrice($data->dataClearStr($arrJSONIn['price']));

        //тут заполняем товарами заказ
        $arrOrderItems = [];
        $entRep = new ActionsRepository($em, $em->getClassMetadata(get_class(new Products())));
        foreach ($arrJSONIn['order'] as $orderItem) {
            $entRep->addRelationToEntity($productId, $action->getId(), 'order', $orderItem['itemType']);
            
        }

        $em->persist($order);

        try {
            $em->flush();
        } catch (UniqueConstraintViolationException $e) {
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
        $response = $out->json($order->getId());


        header('Access-Control-Allow-Origin: *');
        return $response;
    }


    public function editAction($id, Request $request)
    {
        $data = new Data();
        $out = new Out();

        $id = $data->dataClearInt($id);

        $arrJSONIn = json_decode($request->getContent(), true, 10);

        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('RgApiBundle:Orders')->findOneBy(['id' => $id]);

        $caught = false;

        //если подписка не найдена
        if (!$order) {
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

        if (isset($arrJSONIn['userId']))
            $ordersList['userId'] = $order->setUserId($data->dataClearInt($arrJSONIn['userId']));
        if (isset($arrJSONIn['zoneId']))
            $ordersList['zoneId'] = $order->setZoneId($data->dataClearInt($arrJSONIn['zoneId']));
        if (isset($arrJSONIn['subscribeId']))
            $ordersList['subscribeId'] = setSubscribeId($data->dataClearInt($arrJSONIn['subscribeId']));
        if (isset($arrJSONIn['date']))
            $ordersList['date'] = setDate(date_create($arrJSONIn['date']), 'Y-m-d H:i:s');
        if (isset($arrJSONIn['address']))
            $ordersList['address'] = $order->setAddress($data->dataClearInt($arrJSONIn['address']));
        if (isset($arrJSONIn['price']))
            $ordersList['price'] = $order->setPrice($data->dataClearStr($arrJSONIn['price']));

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
            header('Access-Control-Allow-Origin: *');
            return $response;
        }


        $arr = [print_r($request->getContent(), true)];
        $response = $out->json($arr);

        header('Access-Control-Allow-Origin: *');
        return $response;
    }


}
