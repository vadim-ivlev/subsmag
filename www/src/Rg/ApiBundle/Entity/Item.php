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
    private $duration;

    /**
     * @var integer
     */
    private $quantity;

    /**
     * @var integer
     */
    private $timeunit_amount;

    /**
     * @var float
     */
    private $discount;

    /**
     * @var float
     */
    private $cost;

    /**
     * @var float
     */
    private $cat_cost;

    /**
     * @var float
     */
    private $del_cost;

    /**
     * @var float
     */
    private $discounted_cat_cost;

    /**
     * @var float
     */
    private $discounted_del_cost;

    /**
     * @var float
     */
    private $total;

    /**
     * @var \Rg\ApiBundle\Entity\Order
     */
    private $order;

    /**
     * @var \Rg\ApiBundle\Entity\Month
     */
    private $month;

    /**
     * @var \Rg\ApiBundle\Entity\Tariff
     */
    private $tariff;

    /**
     * @var \Rg\ApiBundle\Entity\Promo
     */
    private $promo;


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
     * Set duration
     *
     * @param integer $duration
     *
     * @return Item
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
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
     * Set timeunitAmount
     *
     * @param integer $timeunitAmount
     *
     * @return Item
     */
    public function setTimeunitAmount($timeunitAmount)
    {
        $this->timeunit_amount = $timeunitAmount;

        return $this;
    }

    /**
     * Get timeunitAmount
     *
     * @return integer
     */
    public function getTimeunitAmount()
    {
        return $this->timeunit_amount;
    }

    /**
     * Set discount
     *
     * @param float $discount
     *
     * @return Item
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
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
     * Set catCost
     *
     * @param float $catCost
     *
     * @return Item
     */
    public function setCatCost($catCost)
    {
        $this->cat_cost = $catCost;

        return $this;
    }

    /**
     * Get catCost
     *
     * @return float
     */
    public function getCatCost()
    {
        return $this->cat_cost;
    }

    /**
     * Set delCost
     *
     * @param float $delCost
     *
     * @return Item
     */
    public function setDelCost($delCost)
    {
        $this->del_cost = $delCost;

        return $this;
    }

    /**
     * Get delCost
     *
     * @return float
     */
    public function getDelCost()
    {
        return $this->del_cost;
    }

    /**
     * Set discountedCatCost
     *
     * @param float $discountedCatCost
     *
     * @return Item
     */
    public function setDiscountedCatCost($discountedCatCost)
    {
        $this->discounted_cat_cost = $discountedCatCost;

        return $this;
    }

    /**
     * Get discountedCatCost
     *
     * @return float
     */
    public function getDiscountedCatCost()
    {
        return $this->discounted_cat_cost;
    }

    /**
     * Set discountedDelCost
     *
     * @param float $discountedDelCost
     *
     * @return Item
     */
    public function setDiscountedDelCost($discountedDelCost)
    {
        $this->discounted_del_cost = $discountedDelCost;

        return $this;
    }

    /**
     * Get discountedDelCost
     *
     * @return float
     */
    public function getDiscountedDelCost()
    {
        return $this->discounted_del_cost;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return Item
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
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
     * Set month
     *
     * @param \Rg\ApiBundle\Entity\Month $month
     *
     * @return Item
     */
    public function setMonth(\Rg\ApiBundle\Entity\Month $month = null)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return \Rg\ApiBundle\Entity\Month
     */
    public function getMonth()
    {
        return $this->month;
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

    /**
     * Set promo
     *
     * @param \Rg\ApiBundle\Entity\Promo $promo
     *
     * @return Item
     */
    public function setPromo(\Rg\ApiBundle\Entity\Promo $promo = null)
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get promo
     *
     * @return \Rg\ApiBundle\Entity\Promo
     */
    public function getPromo()
    {
        return $this->promo;
    }
}

