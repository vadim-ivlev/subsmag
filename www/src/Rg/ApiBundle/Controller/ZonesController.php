<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Rg\ApiBundle\Entity\Zones as Zones;
use Rg\ApiBundle\Entity\Promocodes as Promocodes;
use Rg\ApiBundle\Controller\DataProcessing as Data;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class ZonesController extends Controller
{

    //показать все
    public function indexAction()
    {
        $data = new Data();
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $zones = $em->getRepository('RgApiBundle:Zones')->findAll();

        if (!$zones) {
            $arrError = [
                'status' => "error",
                'description' => 'Зоны не найдены!',
                'code' => 200,
                'id' => null
            ];
            return $out->json($arrError);
        }

        foreach ($zones as $key => $zone) {
            $zonesResp[$key]['id'] = $data->dataClearInt($zone->getId());
            $zonesResp[$key]['zoneNumber'] = $data->dataClearInt($zone->getZoneNumber());
            $zonesResp[$key]['tarifId'] = $data->dataClearInt($zone->getTarifId());
        }

        $response = $out->json($zonesResp);

        return $response;
    }

    //получить зону для нужного региона
    public function zonebyregionAction($region)
    {
        $data = new Data();
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        //очищаем запрос от лишнего (остаются только кириллица и пробелы)
        $query = trim(preg_replace("~[^а-яА-Я]|\s~u", "", urldecode($region)));
        if (!empty($query)) {

            //берем id региона
            $area = $em->getRepository('RgApiBundle:Areas')->findOneBy(['nameArea' => $query]);
            //берем зону
            $zone = $em->getRepository('RgApiBundle:Zones')->findOneBy(['id' => $area->getId()]);
//TODO: $tarifList = $em->getRepository('RgApiBundle:Tarif')->findBy(['id' => $zone->getId()]);


            $zoneId['zone'] = $data->dataClearInt($zone->getZoneNumber());

            return $out->json($zoneId);

            //а вдруг пришел кривой запрос или есть попытка включить какую-нибудь бяку
        } else {
            $arrError = [
                'status' => "error",
                'description' => 'Не верный запрос!',
                'code' => 200,
                'id' => null
            ];
            return $out->json($arrError);
        }

        $response = $out->json($zonesResp);

        return $response;
    }

    //получить регионы для нужной зоны
    public function regionsbyzoneAction($zoneNumber)
    {
        $data = new Data();
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $zoneNumber = $data->dataClearInt($zoneNumber);

        if (!empty($zoneNumber)) {

            //берем зону
            $zoneAreas = $em->getRepository('RgApiBundle:Zones')->findBy(['zoneNumber' => $zoneNumber]);

            foreach ($zoneAreas as $zoneArea) {
                $areas[$zoneArea->getId()] = $em->getRepository('RgApiBundle:Areas')->findOneBy(['id' => $zoneArea->getAreaId()])->getNameArea();
            }
            asort($areas);

            //берем регионы
//            $areas = $em->getRepository('RgApiBundle:Areas')->findBy(['actionId' => $actionsList['id']]);
//            if (!$areas) {
//                $actionsList['promocodes'] = [];
//            } else {
//                foreach ($promocodes as $keyPC => $promocode) {
//                    $actionsList['promocodes'][$keyPC]['id'] = $data->dataClearStr($promocode->getId());
//                    $actionsList['promocodes'][$keyPC]['code'] = $data->dataClearStr($promocode->getCode());
//                    $actionsList['promocodes'][$keyPC]['dateStart'] = $promocode->getDateStart()->format('Y-m-d H:i:s');
//                    $actionsList['promocodes'][$keyPC]['dateEnd'] = $promocode->getDateStart()->format('Y-m-d H:i:s');
//                    $actionsList['promocodes'][$keyPC]['flagUsed'] = $promocode->getFlagUsed();
//                    $actionsList['promocodes'][$keyPC]['actionId'] = $promocode->getActionId();
//                }
//            }

            $response = $out->json($areas);

            return($response);

//TODO: $tarifList = $em->getRepository('RgApiBundle:Tarif')->findBy(['id' => $zone->getId()]);


            $zoneId['zone'] = $data->dataClearInt($zone->getZoneNumber());

            return $out->json($zoneId);

            //а вдруг пришел кривой запрос или есть попытка включить какую-нибудь бяку
        } else {
            $arrError = [
                'status' => "error",
                'description' => 'Не верный запрос!',
                'code' => 200,
                'id' => null
            ];
            return $out->json($arrError);
        }

        ;
        $response = $out->json($zonesResp);

        return $response;
    }

    public function showAction($id)
    {
        $data = new Data();
        $out = new Out();

        $id = $data->dataClearInt($id);

        $em = $this->getDoctrine()->getManager();

        $action = $em->getRepository('RgApiBundle:Zones')->findOneBy(['id' => $id]);

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

        $zonesList['id'] = $data->dataClearInt($action->getId());
        $zonesList['zoneNumber'] = $data->dataClearInt($action->getZoneNumber());
        $zonesList['tarifId'] = $data->dataClearInt($action->getTarifId());

        //собираем JSON для вывода
        $response = $out->json($zonesList);

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
        $action = new Zones();
        $action->setZoneNumber($data->dataClearInt($arrJSONIn['zoneNumber']));
        $action->setTarifId($data->dataClearInt($arrJSONIn['tarifId']));

        $em->persist($action);
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

        $action = $em->getRepository('RgApiBundle:Zones')->findOneBy(['id' => $id]);

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

        if (isset($arrJSONIn['zoneNumber']))
            $zonesList['zoneNumber'] = $action->setZoneNumber($data->dataClearInt($arrJSONIn['zoneNumber']));
        if (isset($arrJSONIn['tarifId']))
            $zonesList['tarifId'] = $action->setTarifId($data->dataClearInt($arrJSONIn['tarifId']));


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
