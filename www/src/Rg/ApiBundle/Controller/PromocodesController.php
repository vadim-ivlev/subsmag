<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Rg\ApiBundle\Entity\Promocodes as Promocodes;
use Rg\ApiBundle\Controller\DataProcessing as Data;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class PromocodesController extends Controller
{

    //показать все
    public function indexAction(Request $request)
    {
        $data = new Data();
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $promocodes = $em->getRepository('RgApiBundle:Promocodes')->findAll();

        if (!$promocodes) {
            $arrError = [
                'status' => "error",
                'description' => 'Акции не найдены!',
                'code' => 200,
                'id' => null
            ];
            header('Access-Control-Allow-Origin: *');
            return $out->json($arrError);
//            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        foreach ($promocodes as $key => $promocode) {
            $promocodesList[$key]['id'] = $data->dataClearStr($promocode->getId());
            $promocodesList[$key]['code'] = $data->dataClearStr($promocode->getCode());
            $promocodesList[$key]['dateStart'] = $promocode->getDateStart()->format('Y-m-d H:i:s');
            $promocodesList[$key]['dateEnd'] = $promocode->getDateStart()->format('Y-m-d H:i:s');
            $promocodesList[$key]['flagUsed'] = $promocode->getFlagUsed();
            $promocodesList[$key]['actionId'] = $promocode->getActionId();
        }

        $response = $out->json($promocodesList);

        header('Access-Control-Allow-Origin: *');
        return $response;
//        return $this->render('RgApiBundle:Default:index.html.twig');
    }

    public function showAction($id)
    {
        $data = new Data();
        $out = new Out();
        
        $id = $data->dataClearInt($id);

        $em = $this->getDoctrine()->getManager();

        $promocode = $em->getRepository('RgApiBundle:Promocodes')->findOneBy(['id' => $id]);

        //если пользователь не найден
        if (!$promocode) {
            $arrError = [
                'status' => "error",
                'description' => 'Промокод не найден!',
                'code' => 200,
                'id' => null
            ];
            $response = $out->json($arrError);
            header('Access-Control-Allow-Origin: *');
            return $response;
//            throw $this->createNotFoundException('Unable to find post.');
        }

        $promocodesList['id'] = $data->dataClearStr($promocode->getId());
        $promocodesList['code'] = $data->dataClearStr($promocode->getCode());
        $promocodesList['dateStart'] = $promocode->getDateStart()->format('Y-m-d H:i:s');
        $promocodesList['dateEnd'] = $promocode->getDateStart()->format('Y-m-d H:i:s');
        $promocodesList['flagUsed'] = $promocode->getFlagUsed();
        $promocodesList['actionId'] = $promocode->getActionId();

        //собираем JSON для вывода
        $response = $out->json($promocodesList);

        header('Access-Control-Allow-Origin: *');
        return $response;

//        return $this->render('RgApiBundle:Default:index.html.twig');
    }

    // id - id Акции
    public function showPromocodeByAction($id)
    {
        $data = new Data();
        $out = new Out();

        $id = $data->dataClearInt($id);

        $em = $this->getDoctrine()->getManager();

        $promocodes = $em->getRepository('RgApiBundle:Promocodes')->findBy(['actionId' => $id]);

        //если пользователь не найден
        if (!$promocodes) {
//            $arrError = [
//                'status' => "error",
//                'description' => 'Промокоды не найдены!',
//                'code' => 200,
//                'id' => null
//            ];
            $response = $out->json(null);
            return $response;
        }

        foreach ($promocodes as $key => $promocode) {
            $promocodesList[$key]['id'] = $data->dataClearStr($promocode->getId());
            $promocodesList[$key]['code'] = $data->dataClearStr($promocode->getCode());
            $promocodesList[$key]['dateStart'] = $promocode->getDateStart()->format('Y-m-d H:i:s');
            $promocodesList[$key]['dateEnd'] = $promocode->getDateStart()->format('Y-m-d H:i:s');
            $promocodesList[$key]['flagUsed'] = $promocode->getFlagUsed();
            $promocodesList[$key]['actionId'] = $promocode->getActionId();
        }

        //собираем JSON для вывода
        $response = $out->json($promocodesList);

        header('Access-Control-Allow-Origin: *');
        return $response;
    }

//    public function createAction(Request $request)
//    {
//        $data = new Data();
//        $out = new Out();
//
//        $arrJSONIn = json_decode($request->getContent(), true, 10);
//
//        //если пришел не JSON или не удалось его декодить
//        if (!is_array($arrJSONIn)) {
//            $arrError = [
//                'status' => "error",
//                'description' => "Некорректный запрос в POST!",
//                'code' => 200,
//                'id' => null
//            ];
//            $response = $out->json($arrError);
//            return $response;
//        }
//
//        $caught = false;
//
//        if (count($arrJSONIn) === 1) {
//            $arrJSONInTmp = $arrJSONIn;
//            unset($arrJSONIn);
//            $arrJSONIn[0] = $arrJSONInTmp;
//            unset($arrJSONInTmp);
//        }
//        //добавляем запись(-и)
//        foreach ($arrJSONIn as $key => $arrItem) {
//            $em = $this->get('doctrine')->getManager();
//            $promocode = new Promocodes();
//            $promocode->setCode($data->dataClearStr($arrItem[$key]['code']));
//            $promocode->setDateStart(date_create($data->dataClearStr($arrItem[$key]['dateStart'])), 'Y-m-d H:i:s');
//            $promocode->setDateEnd(date_create($data->dataClearStr($arrItem[$key]['dateEnd'])), 'Y-m-d H:i:s');
//            $promocode->setFlagUsed($arrItem[$key]['flagUsed']);
//            $promocode->setActionId($data->dataClearInt($arrItem[$key]['actionId']));
//            $em->persist($promocode);
//        }
//        try {
//            $em->flush();
//        }
//        catch (UniqueConstraintViolationException $e) {
//            //собираем JSON для вывода ошибки
//            $arrError = [
//                'status' => "error",
//                'description' => $e->getMessage(),
//                'sqlState' => $e->getSQLState(),
//                'errorCode' => $e->getErrorCode(),
//                'id' => null
//            ];
//            $response = $out->json($arrError);
//            $caught = true;
//        }
//
//        if (!$caught) {
//            //собираем JSON для вывода, если ошибок нет
//            $response = $out->json($promocode->getId());
//        }
//
//        return $response;
//    }

    public function editAction($id, Request $request)
    {
        $data = new Data();
        $out = new Out();

        $id = $data->dataClearInt($id);

        $arrJSONIn = json_decode($request->getContent(), true, 10);

        $em = $this->getDoctrine()->getManager();

        $promocode = $em->getRepository('RgApiBundle:Promocodes')->findOneBy(['id' => $id]);

        $caught = false;

        //если промокод не найден
        if (!$promocode) {
            //собираем JSON для вывода ошибки
            $arrError = [
                'status' => "error",
                'description' => 'Промокод не найден!',
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

        if (isset($arrJSONIn['code']))
            $promocodesList['code'] = $promocode->setCode($data->dataClearStr($arrJSONIn['code']));
        if (isset($arrJSONIn['dateStart']))
            $promocodesList['dateStart'] = $promocode->setDateStart(date_create($data->dataClearStr($arrJSONIn['dateStart'])), 'Y-m-d H:i:s');
        if (isset($arrJSONIn['dateEnd']))
            $promocodesList['dateEnd'] = $promocode->setDateEnd(date_create($data->dataClearStr($arrJSONIn['dateEnd'])), 'Y-m-d H:i:s');
        if (isset($arrJSONIn['flagUsed']))
            $promocodesList['flagUsed'] = $promocode->setFlagUsed($arrJSONIn['flagUsed']);
        if (isset($arrJSONIn['actionId']))
            $promocodesList['actionId'] = $promocode->setActionId($data->dataClearInt($arrJSONIn['actionId']));

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
