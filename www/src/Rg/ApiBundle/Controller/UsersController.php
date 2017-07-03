<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Rg\ApiBundle\Entity\Users as Users;
use Rg\ApiBundle\Controller\DataProcessing as Data;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class UsersController extends Controller
{

    //показать все
    public function indexAction(Request $request)
    {
        $data = new Data();
        $out = new Outer();

        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('RgApiBundle:Users')->findAll();

        if (!$users) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        foreach ($users as $key => $user) {
            $usersList[$key]['id'] = $data->dataClearStr($user->getId());
            $usersList[$key]['login'] = $data->dataClearStr($user->getLogin());
            $usersList[$key]['password'] = $data->dataClearStr($user->getPassword());
            $usersList[$key]['userKey'] = $data->dataClearStr($user->getUserKey());
            $usersList[$key]['dateRegistration'] = $user->getDateRegistration()->format('Y-m-d H:i:s');
            $usersList[$key]['dateLastlogin'] = $user->getDateLastlogin()->format('Y-m-d H:i:s');
            $usersList[$key]['flagCanRest'] = $user->getFlagCanRest();
        }

        //собираем JSON для вывода
        $response = $out->json($usersList);

        header('Access-Control-Allow-Origin: *');
        return $response;
//        return $this->render('RgApiBundle:Default:index.html.twig');
    }

    public function showAction($id)
    {
        $data = new Data();
        $out = new Outer();

        $id = $data->dataClearInt($id);
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('RgApiBundle:Users')->findOneBy(['id' => $id]);

        //если пользователь не найден
        if (!$user) {
            $arrError = [
                'status' => "error",
                'description' => 'Пользователь не найден!',
                'code' => 200,
                'id' => null
            ];
            $response = $out->json($arrError);
            return $response;
//            throw $this->createNotFoundException('Unable to find post.');
        }

        $usersList['id'] = $data->dataClearStr($user->getId());
        $usersList['login'] = $data->dataClearStr($user->getLogin());
        $usersList['password'] = $data->dataClearStr($user->getPassword());
        $usersList['userKey'] = $data->dataClearStr($user->getUserKey());
        $usersList['dateRegistration'] = $user->getDateRegistration()->format('Y-m-d H:i:s');
        $usersList['dateLastlogin'] = $user->getDateLastlogin()->format('Y-m-d H:i:s');
        $usersList['flagCanRest'] = $user->getFlagCanRest();

        //собираем JSON для вывода
        $response = $out->json($usersList);

        header('Access-Control-Allow-Origin: *');
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
            header('Access-Control-Allow-Origin: *');
            return $response;
        }

        $caught = false;

        //добавляем запись
        $em = $this->get('doctrine')->getManager();
        $user = new Users();
        $user->setLogin($data->dataClearStr($arrJSONIn['login']));
        $user->setPassword($this->genPassword(trim($arrJSONIn['password']))); // MD5
        $user->setUserKey($data->dataClearStr($arrJSONIn['userKey']));
        $user->setDateRegistration(new \Datetime());
        $user->setDateLastlogin(date_create("0000-00-00"), 'Y-m-d H:i:s');
        $user->setFlagCanRest($arrJSONIn['flagCanRest']);
        $em->persist($user);
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
            $response = $out->json($user->getId());
        }

        header('Access-Control-Allow-Origin: *');
        return $response;
    }

    public function editAction($id, Request $request)
    {
        $data = new Data();
        $out = new Outer();

        $id = $data->dataClearInt($id);

        $arrJSONIn = json_decode($request->getContent(), true, 10);

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('RgApiBundle:Users')->findOneBy(['id' => $id]);

        $caught = false;

        //если пользователь не найден
        if (!$user) {
            //собираем JSON для вывода ошибки
            $arrError = [
                'status' => "error",
                'description' => 'Пользователь не найден!',
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

        if (isset($arrJSONIn['login']))
            $usersList['login'] = $user->setLogin($data->dataClearStr($arrJSONIn['login']));
        if (isset($arrJSONIn['password']))
            $usersList['password'] = $user->setPassword($this->genPassword(trim($arrJSONIn['password'])));
        if (isset($arrJSONIn['userKey']))
            $usersList['userKey'] = $user->setUserKey($data->dataClearStr($arrJSONIn['userKey']));
//        if (isset($arrJSONIn['dateRegistration']))
//            $usersList['dateRegistration'] = $user->setDateRegistration(new \Datetime());
        if (isset($arrJSONIn['dateLastlogin']))
            $usersList['dateLastlogin'] = $user->setDateLastlogin(date_create($data->dataClearStr($arrJSONIn['dateLastlogin'])), 'Y-m-d H:i:s');
        if (isset($arrJSONIn['flagCanRest']))
            $usersList['flagCanRest'] = $user->setFlagCanRest($arrJSONIn['flagCanRest']);

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

    private function genPassword($password) {
        return md5(trim($password));
    }

}
