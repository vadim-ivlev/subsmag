<?php
/**
 * Created by PhpStorm.
 * User: merinov
 * Date: 13.06.17
 * Time: 8:33
 */

namespace Rg\ApiBundle\Controller;


/*
  Класс для очистки входящих данных
  Использовать для данных получаемых из базы, записываемых в базу, получаемых от пользователя
*/

/**
 * Class DataProcessing
 * @package Rg\ApiBundle\Controller
 */
class DataProcessing
{
    //нужен для очистки строковых данных
    /**
     * @param string $str
     * @return string
     */
    public function dataClearStr($str = '') {
        return trim(htmlspecialchars(strip_tags($str)));
    }

    //нужен для очистки Integer
    /**
     * @param int $int
     * @return int
     */
    public function dataClearInt($int = 0) {
        return $int * 1;
    }

}