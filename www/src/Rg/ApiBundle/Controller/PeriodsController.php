<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Rg\ApiBundle\Controller\DataProcessing as Data;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class PeriodsController extends Controller
{

    //показать все
    public function indexAction(Request $request)
    {
        $out = new Out();

        //берем параметры из config.yml
        $periodsList = $this->getParameter('periods');

        $response = $out->json($periodsList);

        return $response;
    }

    public function showAction($id)
    {
        $data = new Data();
        $out = new Out();

        $id = $data->dataClearInt($id);

        //берем параметры из config.yml
        $periodsList = $this->container->getParameter('periods');

        if ($periodsList[$id]) {
            $arrOut = [$id => $periodsList[$id]];
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
        $period = new Periods();
        $period->setMonthStart($data->dataClearInt($arrJSONIn['monthStart']));
        $period->setYearStart($data->dataClearInt($arrJSONIn['yearStart']));
        $period->setPeriodMonths($data->dataClearInt($arrJSONIn['periodMonths'])); //Количество месяцев (зеленое поле в таблице - 4 мес.)
        $period->setQuantityMonthsStart($data->dataClearInt($arrJSONIn['quantityMonthsStart']));
        $period->setQuantityMonthsEnd($data->dataClearInt($arrJSONIn['quantityMonthsEnd']));
        $period->setSubscribeId($data->dataClearInt($arrJSONIn['subscribeId']));

        $em->persist($period);

        //products и kits добавляются ниже!
//        $action->setProductId($data->dataClearInt($arrJSONIn['productId']));
//        $action->setKitId($data->dataClearInt($arrJSONIn['kitId']));

//        $action->setUserId($data->dataClearInt($arrJSONIn['userId']));

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
        $response = $out->json($period->getId());


        return $response;
    }


    public function editAction($id, Request $request)
    {
        $data = new Data();
        $out = new Out();

        $id = $data->dataClearInt($id);

        $arrJSONIn = json_decode($request->getContent(), true, 10);

        $em = $this->getDoctrine()->getManager();

        $period = $em->getRepository('RgApiBundle:Periods')->findOneBy(['id' => $id]);

        $caught = false;

        //если подписка не найдена
        if (!$period) {
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

        if (isset($arrJSONIn['monthStart']))
            $periodsList['monthStart'] = $period->setMonthStart($data->dataClearInt($arrJSONIn['monthStart']));
        if (isset($arrJSONIn['yearStart']))
            $periodsList['yearStart'] = $period->setYearStart($data->dataClearInt($arrJSONIn['yearStart']));
        if (isset($arrJSONIn['periodMonths']))
            $periodsList['periodMonths'] = $period->setPeriodMonths($data->dataClearInt($arrJSONIn['periodMonths']));
        if (isset($arrJSONIn['quantityMonthsStart']))
            $periodsList['quantityMonthsStart'] = $period->setQuantityMonthsStart($data->dataClearInt($arrJSONIn['quantityMonthsStart']));
        if (isset($arrJSONIn['quantityMonthsEnd']))
            $periodsList['quantityMonthsEnd'] = $period->setQuantityMonthsEnd($data->dataClearInt($arrJSONIn['quantityMonthsEnd']));
        if (isset($arrJSONIn['periodId']))
            $periodsList['periodId'] = $period->setPeriodId($data->dataClearInt($arrJSONIn['periodId']));

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
