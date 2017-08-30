<?php
/**
 * Created by PhpStorm.
 * User: sergei
 * Date: 14.08.17
 * Time: 11:10
    {
        "id": 1,
        "medium": 1,
        "delivery": 1,
        "sale": 27,
        "tariff": 1,
        "duration": 3,
        "quantity": 1
    }
 */

namespace Rg\ApiBundle\Cart;

/**
 * Class CartItem
 *
 * @package Rg\ApiBundle\Service
 */
class CartItem implements \JsonSerializable
{
    private $first_month;
    private $duration;
    private $year;
    private $tariff;
    private $quantity;

    public function __construct(
        int $first_month,
        int $duration,
        int $year,
        int $tariff,
        int $quantity
    )
    {
        $this->setFirstMonth($first_month);
        $this->setDuration($duration);
        $this->setYear($year);
        $this->tariff = $tariff;
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getTariff()
    {
        return $this->tariff;
    }

    /**
     * @param int $tariff
     */
    public function setTariff(int $tariff)
    {
        $this->tariff = $tariff;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration(int $duration)
    {
        $condition = $duration > 0 && $duration < 13;
        if (!$condition)
            throw new \Exception('Wrong duration. Min: 1, Max: 12');

        $this->duration = $duration;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getFirstMonth(): int
    {
        return $this->first_month;
    }

    /**
     * @param int $first_month
     * @throws \Exception
     */
    public function setFirstMonth(int $first_month)
    {
        $condition = $first_month > 0 && $first_month < 13;

        if (!$condition)
            throw new \Exception('Wrong first month number. Min: 1, Max: 12');

        $this->first_month = $first_month;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year)
    {
        $condition = $year > 2016 && $year < 2099;

        if (!$condition)
            throw new \Exception('Wrong year. Min: 2017, Max: 2099');

        $this->year = $year;
    }

    function jsonSerialize()
    {
        return [
            "first_month" => $this->getFirstMonth(),
            "duration" => $this->getDuration(),
            "year" => $this->getYear(),
            "tariff" => $this->getTariff(),
            "quantity" => $this->getQuantity(),
        ];
    }
}