<?php
/**
 * Created by PhpStorm.
 * User: sergei
 * Date: 17.07.17
 * Time: 17:19
 */

namespace Rg\ApiBundle\Service;


use Rg\ApiBundle\Entity\Edition;

class PeriodTimeunitConverter
{
    const MONTH = 2048;
    const FIRSTHALF = 4032;
    const SECONDHALF = 63;
    const FULLYEAR = 4095;

    public function convertPeriodStartDurationToTimeunitMask(int $start, int $duration) {
        if ($start == 1 and $duration == 6) return self::FIRSTHALF;
        if ($start == 7 and $duration == 6) return self::SECONDHALF;
        if ($start == 1 and $duration == 12) return self::FULLYEAR;
        if ($start > 0 and $start < 13 and $duration < 12) return self::MONTH;

        throw new \Exception('Probably wrong period parameters were sent.');
    }
}