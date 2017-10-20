<?php

namespace Rg\ApiBundle\Service;

use Rg\ApiBundle\Entity\Item;

class ItemName
{
    const MONTH = [2048, 32];

    public function form(Item $item): string
    {
        $tariff = $item->getTariff();

        if (in_array($tariff->getTimeunit()->getBitmask(), self::MONTH)) {
            $first_month = $item->getMonth()->getNumber();
            $last_month = $first_month + $item->getDuration() - 1;
            $year = $item->getMonth()->getYear();
            $period = $first_month . "-" . $last_month . " " . $year;
        } else {
            $period = $tariff->getTimeunit()->getName() . " " . $tariff->getTimeunit()->getYear();
        }

        $name = $tariff->getProduct()->getName() . " " . $period;

        return $name;
    }
}