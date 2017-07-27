<?php

namespace Rg\ApiBundle\Entity;

/**
 * Item
 */
class Item
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $quantity;

    /**
     * @var float
     */
    private $cost;

    /**
     * @var \Rg\ApiBundle\Entity\Order
     */
    private $order;

    /**
     * @var \Rg\ApiBundle\Entity\Good
     */
    private $good;

    /**
     * @var \Rg\ApiBundle\Entity\Tariff
     */
    private $tariff;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Item
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set cost
     *
     * @param float $cost
     *
     * @return Item
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set order
     *
     * @param \Rg\ApiBundle\Entity\Order $order
     *
     * @return Item
     */
    public function setOrder(\Rg\ApiBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Rg\ApiBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set good
     *
     * @param \Rg\ApiBundle\Entity\Good $good
     *
     * @return Item
     */
    public function setGood(\Rg\ApiBundle\Entity\Good $good = null)
    {
        $this->good = $good;

        return $this;
    }

    /**
     * Get good
     *
     * @return \Rg\ApiBundle\Entity\Good
     */
    public function getGood()
    {
        return $this->good;
    }

    /**
     * Set tariff
     *
     * @param \Rg\ApiBundle\Entity\Tariff $tariff
     *
     * @return Item
     */
    public function setTariff(\Rg\ApiBundle\Entity\Tariff $tariff = null)
    {
        $this->tariff = $tariff;

        return $this;
    }

    /**
     * Get tariff
     *
     * @return \Rg\ApiBundle\Entity\Tariff
     */
    public function getTariff()
    {
        return $this->tariff;
    }
}
