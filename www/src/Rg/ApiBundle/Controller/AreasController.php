<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Rg\ApiBundle\Entity\Areas as Areas;
use Rg\ApiBundle\Entity\Promocodes as Promocodes;
use Rg\ApiBundle\Controller\DataProcessing as Data;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class AreasController extends Controller
{

    //показать все
    public function indexAction(Request $request)
    {
        $data = new Data();
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $areas = $em->getRepository('RgApiBundle:Areas')->findAll();

        if (!$areas) {
            $arrError = [
                'status' => "error",
                'description' => 'Зоны не найдены!',
                'code' => 200,
                'id' => null
            ];
            return $out->json($arrError);
        }

        foreach ($areas as $key => $zone) {
            $areasList[$key]['id'] = $data->dataClearInt($zone->getId());
            $areasList[$key]['nameArea'] = $data->dataClearStr($zone->getNameArea());
        }

        $response = $out->json($areasList);

        return $response;
    }

    public function showAction($id)
    {
        $data = new Data();
        $out = new Out();

        $id = $data->dataClearInt($id);

        $em = $this->getDoctrine()->getManager();

        $action = $em->getRepository('RgApiBundle:Areas')->findOneBy(['id' => $id]);

        //если пользователь не найден
        if (!$action) {
            $arrError = [
                'status' => "error",
                'description' => 'Зона не найдена!',
                'code' => 200,
                'id' => null
            ];
            $response = $out->json($arrError);
            return $response;
        }

        $areasList['id'] = $data->dataClearInt($action->getId());
        $areasList['nameArea'] = $data->dataClearStr($action->getNameArea());

        //собираем JSON для вывода
        $response = $out->json($areasList);

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
            return $response;
        }

        $caught = false;

        //добавляем запись
        $em = $this->get('doctrine')->getManager();
        $action = new Areas();
        $action->setNameArea($data->dataClearStr($arrJSONIn['nameArea']));

        $em->persist($action);
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
            $response = $out->json($action->getId());
        }

        return $response;
    }

    public function editAction($id, Request $request)
    {
        $data = new Data();
        $out = new Out();

        $id = $data->dataClearInt($id);

        $arrJSONIn = json_decode($request->getContent(), true, 10);

        $em = $this->getDoctrine()->getManager();

        $action = $em->getRepository('RgApiBundle:Areas')->findOneBy(['id' => $id]);

        $caught = false;

        //если пользователь не найден
        if (!$action) {
            //собираем JSON для вывода ошибки
            $arrError = [
                'status' => "error",
                'description' => 'Зона не найдена!',
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

        if (isset($arrJSONIn['nameArea']))
            $areasList['nameArea'] = $action->setNameArea($data->dataClearStr($arrJSONIn['nameArea']));


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

        return $response;
    }

}
