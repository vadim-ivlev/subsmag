<?php

namespace Rg\ApiBundle\Entity;

/**
 * Pin
 */
class Pin
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var boolean
     */
    private $is_active;

    /**
     * @var \Rg\ApiBundle\Entity\Order
     */
    private $order;

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
     * Set value
     *
     * @param string $value
     *
     * @return Pin
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Pin
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set order
     *
     * @param \Rg\ApiBundle\Entity\Order $order
     *
     * @return Pin
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
     * Set promo
     *
     * @param \Rg\ApiBundle\Entity\Promo $promo
     *
     * @return Pin
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

