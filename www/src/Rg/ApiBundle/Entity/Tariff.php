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
     * @var \Rg\ApiBundle\Entity\Product
     */
    private $product;

    /**
     * @var \Rg\ApiBundle\Entity\Period
     */
    private $period;

    /**
     * @var \Rg\ApiBundle\Entity\Delivery
     */
    private $delivery;

    /**
     * @var \Rg\ApiBundle\Entity\Zone
     */
    private $zone;

    /**
     * @var \Rg\ApiBundle\Entity\Media
     */
    private $media;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $orders;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set period
     *
     * @param \Rg\ApiBundle\Entity\Period $period
     *
     * @return Tariff
     */
    public function setPeriod(\Rg\ApiBundle\Entity\Period $period = null)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get period
     *
     * @return \Rg\ApiBundle\Entity\Period
     */
    public function getPeriod()
    {
        return $this->period;
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
     * Set media
     *
     * @param \Rg\ApiBundle\Entity\Media $media
     *
     * @return Tariff
     */
    public function setMedia(\Rg\ApiBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Rg\ApiBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Add order
     *
     * @param \Rg\ApiBundle\Entity\Order $order
     *
     * @return Tariff
     */
    public function addOrder(\Rg\ApiBundle\Entity\Order $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \Rg\ApiBundle\Entity\Order $order
     */
    public function removeOrder(\Rg\ApiBundle\Entity\Order $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
