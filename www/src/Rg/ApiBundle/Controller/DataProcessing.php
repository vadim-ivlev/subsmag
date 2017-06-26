<?php
/**
 * Created by PhpStorm.
 * User: merinov
 * Date: 13.06.17
 * Time: 8:33
 */

namespace Rg\ApiBundle\Controller;


class DataProcessing
{
    public function dataClearStr($str = '') {
        return trim(htmlspecialchars(strip_tags($str)));
    }

    public function dataClearInt($int = 0) {
        return $int * 1;
    }

}