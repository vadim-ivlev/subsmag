<?php

namespace Rg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Rg\ApiBundle\RgApiBundle;
use Doctrine\ORM\QueryBuilder;
use Rg\ApiBundle\Entity\Users;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $params = [];

        $response = new JsonResponse();

        return $response->setData($params);
    }

}
