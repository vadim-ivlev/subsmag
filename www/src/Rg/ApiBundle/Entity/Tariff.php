<?php

namespace Rg\ApiBundle\Entity;

/**
 * Tariff
 */
class Tariff
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $price;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $items;

    /**
     * @var \Rg\ApiBundle\Entity\Product
     */
    private $product;

    /**
     * @var \Rg\ApiBundle\Entity\Timeunit
     */
    private $timeunit;

    /**
     * @var \Rg\ApiBundle\Entity\Delivery
     */
    private $delivery;

    /**
     * @var \Rg\ApiBundle\Entity\Zone
     */
    private $zone;

    /**
     * @var \Rg\ApiBundle\Entity\Medium
     */
    private $medium;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set price
     *
     * @param float $price
     *
     * @return Tariff
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Add item
     *
     * @param \Rg\ApiBundle\Entity\Item $item
     *
     * @return Tariff
     */
    public function addItem(\Rg\ApiBundle\Entity\Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \Rg\ApiBundle\Entity\Item $item
     */
    public function removeItem(\Rg\ApiBundle\Entity\Item $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set product
     *
     * @param \Rg\ApiBundle\Entity\Product $product
     *
     * @return Tariff
     */
    public function setProduct(\Rg\ApiBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Rg\ApiBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set timeunit
     *
     * @param \Rg\ApiBundle\Entity\Timeunit $timeunit
     *
     * @return Tariff
     */
    public function setTimeunit(\Rg\ApiBundle\Entity\Timeunit $timeunit = null)
    {
        $this->timeunit = $timeunit;

        return $this;
    }

    /**
     * Get timeunit
     *
     * @return \Rg\ApiBundle\Entity\Timeunit
     */
    public function getTimeunit()
    {
        return $this->timeunit;
    }

    /**
     * Set delivery
     *
     * @param \Rg\ApiBundle\Entity\Delivery $delivery
     *
     * @return Tariff
     */
    public function setDelivery(\Rg\ApiBundle\Entity\Delivery $delivery = null)
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * Get delivery
     *
     * @return \Rg\ApiBundle\Entity\Delivery
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * Set zone
     *
     * @param \Rg\ApiBundle\Entity\Zone $zone
     *
     * @return Tariff
     */
    public function setZone(\Rg\ApiBundle\Entity\Zone $zone = null)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return \Rg\ApiBundle\Entity\Zone
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * Set medium
     *
     * @param \Rg\ApiBundle\Entity\Medium $medium
     *
     * @return Tariff
     */
    public function setMedium(\Rg\ApiBundle\Entity\Medium $medium = null)
    {
        $this->medium = $medium;

        return $this;
    }

    /**
     * Get medium
     *
     * @return \Rg\ApiBundle\Entity\Medium
     */
    public function getMedium()
    {
        return $this->medium;
    }
}
