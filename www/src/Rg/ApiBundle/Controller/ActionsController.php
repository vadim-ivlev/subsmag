<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Rg\ApiBundle\Entity\Actions;
use Rg\ApiBundle\Entity\Promocodes;
use Rg\ApiBundle\Entity\Products;
use Rg\ApiBundle\Entity\Kits;
use Rg\ApiBundle\Repository\ActionsRepository;
use Rg\ApiBundle\Controller\DataProcessing as Data;
use Rg\ApiBundle\Controller\Outer as Out;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class ActionsController extends Controller
{

    //показать все
    public function indexAction(Request $request)
    {
        $data = new Data();
        $out = new Out();

        $em = $this->getDoctrine()->getManager();

        $actions = $em->getRepository('RgApiBundle:Actions')->findAll();

        if (!$actions) {
            $arrError = [
                'status' => "error",
                'description' => 'Акции не найдены!',
                'code' => 200,
                'id' => null
            ];
            return $out->json($arrError);
//            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        foreach ($actions as $key => $action) {

            $actionsList[$key]['id'] = $data->dataClearInt($action->getId());
            $actionsList[$key]['name'] = $data->dataClearStr($action->getName());
            $actionsList[$key]['introtext'] = $data->dataClearStr($action->getIntrotext());
            $actionsList[$key]['description'] = $data->dataClearStr($action->getDescription());
            $actionsList[$key]['dateStart'] = date_format($action->getDateStart(), 'Y-m-d H:i:s');
            $actionsList[$key]['dateEnd'] = date_format($action->getDateEnd(), 'Y-m-d H:i:s');
            $actionsList[$key]['discount'] = $data->dataClearStr($action->getDiscount());
            $actionsList[$key]['giftDescription'] = $data->dataClearStr($action->getGiftDescription());
            $actionsList[$key]['flagVisibleOnSite'] = $action->getFlagVisibleOnSite();
            $actionsList[$key]['flagPercentOrFix'] = $action->getFlagPercentOrFix();
            $actionsList[$key]['cntUsed'] = $data->dataClearInt($action->getCntUsed());

//            $actionsList[$key]['idProduct'] = $data->dataClearInt($action->getProductId());
            //собираем издания в акции
            $actionRep = new ActionsRepository($em, $em->getClassMetadata(get_class(new Products())));
            $actionsList[$key]['products'] = $actionRep->getRelationByEntityId($action->getId(), 'product', 'action');

            $actionsList[$key]['kits'] = $data->dataClearInt($action->getKitId());
//            $actionsList[$key]['idUser'] = $data->dataClearInt($action->getUserId());

            $promocodes = $em->getRepository('RgApiBundle:Promocodes')->findBy(['actionId' => $actionsList[$key]['id']]);
            if (!$promocodes) {
                $actionsList[$key]['promocodes'] = [];
            } else {
                foreach ($promocodes as $keyPC => $promocode) {
                    $actionsList[$key]['promocodes'][$keyPC]['id'] = $data->dataClearStr($promocode->getId());
                    $actionsList[$key]['promocodes'][$keyPC]['code'] = $data->dataClearStr($promocode->getCode());
                    $actionsList[$key]['promocodes'][$keyPC]['dateStart'] = $promocode->getDateStart()->format('Y-m-d H:i:s');
                    $actionsList[$key]['promocodes'][$keyPC]['dateEnd'] = $promocode->getDateStart()->format('Y-m-d H:i:s');
                    $actionsList[$key]['promocodes'][$keyPC]['flagUsed'] = $promocode->getFlagUsed();
                    $actionsList[$key]['promocodes'][$keyPC]['actionId'] = $promocode->getActionId();
                }
            }
        }

        $response = $out->json($actionsList);

        return $response;
//        return $this->render('RgApiBundle:Default:index.html.twig');
    }

    public function showAction($id)
    {
        $data = new Data();
        $out = new Out();
        
        $id = $data->dataClearInt($id);

        $em = $this->getDoctrine()->getManager();

        $action = $em->getRepository('RgApiBundle:Actions')->findOneBy(['id' => $id]);

        //если пользователь не найден
        if (!$action) {
            $arrError = [
                'status' => "error",
                'description' => 'Акция не найдена!',
                'code' => 200,
                'id' => null
            ];
            $response = $out->json($arrError);
            return $response;
//            throw $this->createNotFoundException('Unable to find post.');
        }

        $actionsList['id'] = $data->dataClearInt($action->getId());
        $actionsList['name'] = $data->dataClearStr($action->getName());
        $actionsList['introtext'] = $data->dataClearStr($action->getIntrotext());
        $actionsList['description'] = $data->dataClearStr($action->getDescription());
        $actionsList['dateStart'] = $action->getDateStart()->format('Y-m-d H:i:s');
        $actionsList['dateEnd'] = $action->getDateEnd()->format('Y-m-d H:i:s');
        $actionsList['discount'] = $data->dataClearStr($action->getDiscount());
        $actionsList['giftDescription'] = $data->dataClearStr($action->getGiftDescription());
        $actionsList['flagVisibleOnSite'] = $action->getFlagVisibleOnSite();
        $actionsList['flagPercentOrFix'] = $action->getFlagPercentOrFix();
        $actionsList['cntUsed'] = $data->dataClearInt($action->getCntUsed());
//        $actionsList['idProduct'] = $data->dataClearInt($action->getProductId());
//        $actionsList['idKit'] = $data->dataClearInt($action->getKitId());

        //собираем издания в акции
        $actionRep = new ActionsRepository($em, $em->getClassMetadata(get_class(new Products())));
        $actionsList['products'] = $actionRep->getRelationByEntityId($id, 'product', 'action');
        //собираем наборы в акции
        $actionRep = new ActionsRepository($em, $em->getClassMetadata(get_class(new Kits())));
        $actionsList['kits'] = $actionRep->getRelationByEntityId($id, 'kit', 'action');

//        $actionsList['idUser'] = $data->dataClearInt($action->getUserId());

        $promocodes = $em->getRepository('RgApiBundle:Promocodes')->findBy(['actionId' => $actionsList['id']]);
        if (!$promocodes) {
            $actionsList['promocodes'] = [];
        } else {
            foreach ($promocodes as $keyPC => $promocode) {
                $actionsList['promocodes'][$keyPC]['id'] = $data->dataClearStr($promocode->getId());
                $actionsList['promocodes'][$keyPC]['code'] = $data->dataClearStr($promocode->getCode());
                $actionsList['promocodes'][$keyPC]['dateStart'] = $promocode->getDateStart()->format('Y-m-d H:i:s');
                $actionsList['promocodes'][$keyPC]['dateEnd'] = $promocode->getDateStart()->format('Y-m-d H:i:s');
                $actionsList['promocodes'][$keyPC]['flagUsed'] = $promocode->getFlagUsed();
                $actionsList['promocodes'][$keyPC]['actionId'] = $promocode->getActionId();
            }
        }

        //собираем JSON для вывода
        $response = $out->json($actionsList);

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
            return $response;
        }

        $caught = false;

        //добавляем запись
        $em = $this->get('doctrine')->getManager();
        $action = new Actions();
        $action->setName($data->dataClearStr($arrJSONIn['name']));
        $action->setIntrotext($data->dataClearStr($arrJSONIn['introtext']));
        $action->setDescription($data->dataClearStr($arrJSONIn['description']));
        $action->setDateStart(date_create($arrJSONIn['dateStart']), 'Y-m-d H:i:s');
        $action->setDateEnd(date_create($arrJSONIn['dateEnd']), 'Y-m-d H:i:s');
        $action->setDiscount($data->dataClearStr($arrJSONIn['discount']));
        $action->setGiftDescription($data->dataClearStr($arrJSONIn['giftDescription']));
        $action->setFlagVisibleOnSite($data->dataClearStr($arrJSONIn['flagVisibleOnSite']));
        $action->setFlagPercentOrFix($data->dataClearStr($arrJSONIn['flagPercentOrFix']));
        $action->setCntUsed($data->dataClearInt($arrJSONIn['cntUsed']));

        //products и kits добавляются ниже!
//        $action->setProductId($data->dataClearInt($arrJSONIn['productId']));
//        $action->setKitId($data->dataClearInt($arrJSONIn['kitId']));

//        $action->setUserId($data->dataClearInt($arrJSONIn['userId']));

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

        if (!$caught && !empty($arrJSONIn['promocodes'])) {
            //заполняем промокоды
            foreach ($arrJSONIn['promocodes'] as $key => $item) {
                //добавляем каждому промокоду ИД свежесохраненной акции
                $arrJSONIn['promocodes'][$key]['actionId'] = $action->getId();
            }

            //если пришел не JSON или не удалось его декодить
            if (!is_array($arrJSONIn['promocodes'])) {
                $arrError = [
                    'status' => "error",
                    'description' => "Некорректный запрос добавления промокодов в POST!",
                    'code' => 200,
                    'id' => null
                ];
                $response = $out->json($arrError);
                return $response;
            }

            //добавляем записи
            foreach ($arrJSONIn['promocodes'] as $key => $arrItem) {
                $em = $this->get('doctrine')->getManager();
                $promocode = new Promocodes();
                $promocode->setCode($data->dataClearStr($arrItem['code']));
                $promocode->setDateStart(date_create($arrItem['dateStart']), 'Y-m-d H:i:s');
                $promocode->setDateEnd(date_create($arrItem['dateEnd']), 'Y-m-d H:i:s');
                $promocode->setFlagUsed($arrItem['flagUsed']);
                $promocode->setActionId($data->dataClearInt($arrItem['actionId']));
                $em->persist($promocode);
            }

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

            //добавление products
            if (!empty($arrJSONIn['products']) && is_array($arrJSONIn['products']) && count($arrJSONIn['products']) > 0) {
                //если указан(-ы) издания(-ы) - добавляем издание(-я) в в акцию
                if (is_array($arrJSONIn['products']) && count($arrJSONIn['products']) > 0) {
                    $entRep = new ActionsRepository($em, $em->getClassMetadata(get_class(new Products())));
                    foreach ($arrJSONIn['products'] as $productId) {
    //                    $productId= $productId * 1;
                        if ((integer)$productId > 0) {
                            $entRep->addRelationToEntity($productId, $action->getId(), 'product', 'action');
                        }
                    }
                }
            }

            //добавление kits
            if (!empty($arrJSONIn['kits']) && is_array($arrJSONIn['kits']) && count($arrJSONIn['kits']) > 0) {
                //если указан(-ы) наборы(-ы) - добавляем набор(-ы) в акцию
                if (is_array($arrJSONIn['kits']) && count($arrJSONIn['kits']) > 0) {
                    $promocodeRep = new ActionsRepository($em, $em->getClassMetadata(get_class(new Kits())));
                    foreach ($arrJSONIn['kits'] as $productId) {
    //                    $productId= $productId * 1;
                        if ((integer)$productId > 0) {
                            $promocodeRep->addRelationToEntity($productId, $action->getId(), 'kit', 'action');
                        }
                    }
                }
            }

                //собираем JSON для вывода, если ошибок нет
                $response = $out->json($promocode->getId());

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

        $action = $em->getRepository('RgApiBundle:Actions')->findOneBy(['id' => $id]);

        $caught = false;

        //если акция не найдена
        if (!$action) {
            //собираем JSON для вывода ошибки
            $arrError = [
                'status' => "error",
                'description' => 'Акция не найдена!',
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

        if (isset($arrJSONIn['name']))
            $actionsList['name'] = $action->setName($data->dataClearStr($arrJSONIn['name']));
        if (isset($arrJSONIn['introtext']))
            $actionsList['introtext'] = $action->setIntrotext($data->dataClearStr($arrJSONIn['introtext']));
        if (isset($arrJSONIn['description']))
            $actionsList['description'] = $action->setDescription($data->dataClearStr($arrJSONIn['description']));
        if (isset($arrJSONIn['dateStart']))
            $actionsList['dateStart'] = $action->setDateStart(date_create($data->dataClearStr($arrJSONIn['dateStart'])), 'Y-m-d H:i:s');
        if (isset($arrJSONIn['dateEnd']))
            $actionsList['dateEnd'] = $action->setDateEnd(date_create($data->dataClearStr($arrJSONIn['dateEnd'])), 'Y-m-d H:i:s');
        if (isset($arrJSONIn['discount']))
            $actionsList['discount'] = $action->setDiscount($data->dataClearStr($arrJSONIn['discount']));
        if (isset($arrJSONIn['giftDescription']))
            $actionsList['giftDescription'] = $action->setGiftDescription($data->dataClearStr($arrJSONIn['giftDescription']));
        if (isset($arrJSONIn['flagVisibleOnSite']))
            $actionsList['flagVisibleOnSite'] = $action->setFlagVisibleOnSite($data->dataClearStr($arrJSONIn['flagVisibleOnSite']));
        if (isset($arrJSONIn['flagPercentOrFix']))
            $actionsList['flagPercentOrFix'] = $action->setFlagPercentOrFix($data->dataClearStr($arrJSONIn['flagPercentOrFix']));
        if (isset($arrJSONIn['cntUsed']))
            $actionsList['cntUsed'] = $action->setCntUsed($data->dataClearInt($arrJSONIn['cntUsed']));

//        if (isset($arrJSONIn['idProduct']))
//            $actionsList['idProduct'] = $action->setIdProduct($data->dataClearInt($arrJSONIn['idProduct']));

//        if (isset($arrJSONIn['idKit']))
//            $actionsList['idKit'] = $action->setIdKit($data->dataClearInt($arrJSONIn['idKit']));

        //если указан(-ы) товар(-ы) - добавляем товар в комплект(-ы) [МАССИВ!!!]
        if (isset($arrJSONIn['products']) && is_array($arrJSONIn['products']) && count($arrJSONIn['products']) > 0) {
            $actionRep = new ActionsRepository($em, $em->getClassMetadata(get_class(new Products())));
            //сначала удаляем все связи изданиЕ-акциИ
            $actionRep->removeRelationFromEntity(0, $id, 'product', 'action', true);
            //и создаем новые связи издания с комплектами
            foreach ($arrJSONIn['products'] as $productId) {
                if ($productId * 1 > 0) {
                    $actionRep->addRelationToEntity($productId, $id, 'product', 'action');
                }
            }
        }

        //если указан(-ы) товар(-ы) - добавляем товар в комплект(-ы) [МАССИВ!!!]
        if (isset($arrJSONIn['kits']) && is_array($arrJSONIn['kits']) && count($arrJSONIn['kits']) > 0) {
            $actionRep = new ActionsRepository($em, $em->getClassMetadata(get_class(new Kits())));
            //сначала удаляем все связи изданиЕ-акциИ
            $actionRep->removeRelationFromEntity(0, $id, 'kit', 'action', true);
            //и создаем новые связи издания с комплектами
            foreach ($arrJSONIn['kits'] as $productId) {
                if ($productId * 1 > 0) {
                    $actionRep->addRelationToEntity($productId, $id, 'kit', 'action');
                }
            }
        }

//        if (isset($arrJSONIn['idUser']))
//            $actionsList['idUser'] = $action->setIdUser($data->dataClearInt($arrJSONIn['idUser']));
        
        if (isset($arrJSONIn['flagCanRest']))
            $actionsList['flagCanRest'] = $action->setFlagCanRest($arrJSONIn['flagCanRest']);


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
//        return $this->render('RgApiBundle:Default:index.html.twig');
    }

}
