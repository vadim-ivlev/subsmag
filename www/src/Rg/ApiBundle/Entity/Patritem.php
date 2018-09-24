<?php

namespace Rg\ApiBundle\Entity;

/**
 * Patritem
 */
class Patritem
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
     * @var \Rg\ApiBundle\Entity\Patriff
     */
    private $patriff;


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
     * @return Patritem
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
     * @return Patritem
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
     * @return Patritem
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
     * @return Patritem
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
     * @return Patritem
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
     * @return Patritem
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
     * @return Patritem
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
     * @return Patritem
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
     * Set patriff
     *
     * @param \Rg\ApiBundle\Entity\Patriff $patriff
     *
     * @return Patritem
     */
    public function setPatriff(\Rg\ApiBundle\Entity\Patriff $patriff = null)
    {
        $this->patriff = $patriff;

        return $this;
    }

    /**
     * Get patriff
     *
     * @return \Rg\ApiBundle\Entity\Patriff
     */
    public function getPatriff()
    {
        return $this->patriff;
    }
}

