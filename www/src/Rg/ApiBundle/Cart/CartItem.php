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
    private $id;
    private $medium;
    private $delivery;
    private $sale;
    private $tariff;
    private $duration;
    private $quantity;

    public function __construct(
        int $id,
        int $medium,
        int $delivery,
        int $sale,
        int $tariff,
        int $duration,
        int $quantity
    )
    {
        $this->id = $id;
        $this->medium = $medium;
        $this->delivery = $delivery;
        $this->sale = $sale;
        $this->tariff = $tariff;
        $this->duration = $duration;
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getMedium()
    {
        return $this->medium;
    }

    /**
     * @param int $medium
     */
    public function setMedium(int $medium)
    {
        $this->medium = $medium;
    }

    /**
     * @return int
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * @param int $delivery
     */
    public function setDelivery(int $delivery)
    {
        $this->delivery = $delivery;
    }

    /**
     * @return int
     */
    public function getSale()
    {
        return $this->sale;
    }

    /**
     * @param int $sale
     */
    public function setSale(int $sale)
    {
        $this->sale = $sale;
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

    /**
     * @param int $duration
     */
    public function setDuration(int $duration)
    {
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

    function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "medium" => $this->getMedium(),
            "delivery" => $this->getDelivery(),
            "sale" => $this->getSale(),
            "tariff" => $this->getTariff(),
            "duration" => $this->getDuration(),
            "quantity" => $this->getQuantity(),
        ];
    }
}