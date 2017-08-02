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

