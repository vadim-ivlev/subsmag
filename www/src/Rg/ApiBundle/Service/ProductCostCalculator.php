<?php

namespace Rg\ApiBundle\Service;


use Rg\ApiBundle\Entity\Tariff;

class ProductCostCalculator
{
    const MONTH = 2048;

    public function calculateTimeunitAmount(Tariff $tariff, int $duration) {
        $bitmask = $tariff->getTimeunit()->getBitmask();

        if ($bitmask == self::MONTH) {
            return $duration;
        }
        return 1;
    }

    public function calculateItemCost(Tariff $tariff, int $duration)
    {
        $timeunit_amount = $this->calculateTimeunitAmount($tariff, $duration);

        // вычислить стоимость единицы позиции по формуле
        // cost = tu_amount * tariff.price
        $cost = $timeunit_amount * $tariff->getPrice();

        return $cost;
    }
}