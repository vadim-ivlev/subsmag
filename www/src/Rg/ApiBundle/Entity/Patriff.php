<?php

namespace Rg\ApiBundle\Entity;

/**
 * Patriff
 */
class Patriff
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $catalogue_price;

    /**
     * @var float
     */
    private $delivery_price;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $patritems;

    /**
     * @var \Rg\ApiBundle\Entity\Issue
     */
    private $issue;

    /**
     * @var \Rg\ApiBundle\Entity\Medium
     */
    private $medium;

    /**
     * @var \Rg\ApiBundle\Entity\Delivery
     */
    private $delivery;

    /**
     * @var \Rg\ApiBundle\Entity\Zone
     */
    private $zone;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->patritems = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set cataloguePrice
     *
     * @param float $cataloguePrice
     *
     * @return Patriff
     */
    public function setCataloguePrice($cataloguePrice)
    {
        $this->catalogue_price = $cataloguePrice;

        return $this;
    }

    /**
     * Get cataloguePrice
     *
     * @return float
     */
    public function getCataloguePrice()
    {
        return $this->catalogue_price;
    }

    /**
     * Set deliveryPrice
     *
     * @param float $deliveryPrice
     *
     * @return Patriff
     */
    public function setDeliveryPrice($deliveryPrice)
    {
        $this->delivery_price = $deliveryPrice;

        return $this;
    }

    /**
     * Get deliveryPrice
     *
     * @return float
     */
    public function getDeliveryPrice()
    {
        return $this->delivery_price;
    }

    /**
     * Add patritem
     *
     * @param \Rg\ApiBundle\Entity\Patritem $patritem
     *
     * @return Patriff
     */
    public function addPatritem(\Rg\ApiBundle\Entity\Patritem $patritem)
    {
        $this->patritems[] = $patritem;

        return $this;
    }

    /**
     * Remove patritem
     *
     * @param \Rg\ApiBundle\Entity\Patritem $patritem
     */
    public function removePatritem(\Rg\ApiBundle\Entity\Patritem $patritem)
    {
        $this->patritems->removeElement($patritem);
    }

    /**
     * Get patritems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatritems()
    {
        return $this->patritems;
    }

    /**
     * Set issue
     *
     * @param \Rg\ApiBundle\Entity\Issue $issue
     *
     * @return Patriff
     */
    public function setIssue(\Rg\ApiBundle\Entity\Issue $issue = null)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * Get issue
     *
     * @return \Rg\ApiBundle\Entity\Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * Set medium
     *
     * @param \Rg\ApiBundle\Entity\Medium $medium
     *
     * @return Patriff
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

    /**
     * Set delivery
     *
     * @param \Rg\ApiBundle\Entity\Delivery $delivery
     *
     * @return Patriff
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
     * @return Patriff
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
}
