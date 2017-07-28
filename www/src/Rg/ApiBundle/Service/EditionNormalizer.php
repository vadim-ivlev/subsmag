<?php
/**
 * Created by PhpStorm.
 * User: sergei
 * Date: 17.07.17
 * Time: 17:19
 */

namespace Rg\ApiBundle\Service;


use Rg\ApiBundle\Entity\Edition;

class EditionNormalizer
{
    public function convertToArray(Edition $edition) {
        return [
            'id' => $edition->getId(),
            'name' => $edition->getName(),
            'keyword' => $edition->getKeyword(),
            'description' => $edition->getDescription(),
            'frequency' => $edition->getFrequency(),
            'image' => $edition->getImage(),
        ];
    }

    public function convertPeriodStartDurationToTimeunitMask(int $start, int $duration) {
        $mask = 2048;
//2048
//32
//4032
//63
//4095

        return $mask;
    }
}