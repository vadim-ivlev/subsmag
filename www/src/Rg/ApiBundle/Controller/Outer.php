<?php
/**
 * Created by PhpStorm.
 * User: merinov
 * Date: 13.06.17
 * Time: 10:54
 */

namespace Rg\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

/*
  Класс для вывода в JSON
  Используется перед возвратом ответа пользователю
*/

/**
 * Class Outer
 * @package Rg\ApiBundle\Controller
 */
class Outer
{

    /**
     * @param array $out
     * @return JsonResponse
     * @throws \Exception
     */
    public function json($out = []) {
        //собираем JSON для вывода
        $response = new JsonResponse();
        $response
            ->setData($out)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $response;
    }

}