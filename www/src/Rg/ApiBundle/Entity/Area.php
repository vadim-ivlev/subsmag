<?php

namespace Rg\ApiBundle\Entity;

/**
 * Area
 */
class Area
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $from_front_id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $goods;

    /**
     * @var \Rg\ApiBundle\Entity\Zone
     */
    private $zone;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->goods = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Area
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set fromFrontId
     *
     * @param integer $fromFrontId
     *
     * @return Area
     */
    public function setFromFrontId($fromFrontId)
    {
        $this->from_front_id = $fromFrontId;

        return $this;
    }

    /**
     * Get fromFrontId
     *
     * @return integer
     */
    public function getFromFrontId()
    {
        return $this->from_front_id;
    }

    /**
     * Add good
     *
     * @param \Rg\ApiBundle\Entity\Good $good
     *
     * @return Area
     */
    public function addGood(\Rg\ApiBundle\Entity\Good $good)
    {
        $this->goods[] = $good;

        return $this;
    }

    /**
     * Remove good
     *
     * @param \Rg\ApiBundle\Entity\Good $good
     */
    public function removeGood(\Rg\ApiBundle\Entity\Good $good)
    {
        $this->goods->removeElement($good);
    }

    /**
     * Get goods
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGoods()
    {
        return $this->goods;
    }

    /**
     * Set zone
     *
     * @param \Rg\ApiBundle\Entity\Zone $zone
     *
     * @return Area
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $sales;


    /**
     * Add sale
     *
     * @param \Rg\ApiBundle\Entity\Sale $sale
     *
     * @return Area
     */
    public function addSale(\Rg\ApiBundle\Entity\Sale $sale)
    {
        $this->sales[] = $sale;

        return $this;
    }

    /**
     * Remove sale
     *
     * @param \Rg\ApiBundle\Entity\Sale $sale
     */
    public function removeSale(\Rg\ApiBundle\Entity\Sale $sale)
    {
        $this->sales->removeElement($sale);
    }

    /**
     * Get sales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSales()
    {
        return $this->sales;
    }
}
