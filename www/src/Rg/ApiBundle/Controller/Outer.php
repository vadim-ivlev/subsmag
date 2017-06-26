<?php
/**
 * Created by PhpStorm.
 * User: merinov
 * Date: 13.06.17
 * Time: 10:54
 */

namespace Rg\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;


class Outer
{
    public function json($out = []) {
        //собираем JSON для вывода
        $response = new JsonResponse();
        $response
            ->setData($out, JSON_UNESCAPED_UNICODE)
            ->headers->set('Content-Type', 'application/json');
        return $response;
    }

}