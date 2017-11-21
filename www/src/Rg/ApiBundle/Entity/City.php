<?php

namespace Rg\ApiBundle\Entity;

/**
 * City
 */
class City
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
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $works_cid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $orders;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $legal_deliveries;

    /**
     * @var \Rg\ApiBundle\Entity\Area
     */
    private $area;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->legal_deliveries = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return City
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
     * Set type
     *
     * @param string $type
     *
     * @return City
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set worksCid
     *
     * @param integer $worksCid
     *
     * @return City
     */
    public function setWorksCid($worksCid)
    {
        $this->works_cid = $worksCid;

        return $this;
    }

    /**
     * Get worksCid
     *
     * @return integer
     */
    public function getWorksCid()
    {
        return $this->works_cid;
    }

    /**
     * Add order
     *
     * @param \Rg\ApiBundle\Entity\Order $order
     *
     * @return City
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

    /**
     * Add legalDelivery
     *
     * @param \Rg\ApiBundle\Entity\Legal $legalDelivery
     *
     * @return City
     */
    public function addLegalDelivery(\Rg\ApiBundle\Entity\Legal $legalDelivery)
    {
        $this->legal_deliveries[] = $legalDelivery;

        return $this;
    }

    /**
     * Remove legalDelivery
     *
     * @param \Rg\ApiBundle\Entity\Legal $legalDelivery
     */
    public function removeLegalDelivery(\Rg\ApiBundle\Entity\Legal $legalDelivery)
    {
        $this->legal_deliveries->removeElement($legalDelivery);
    }

    /**
     * Get legalDeliveries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLegalDeliveries()
    {
        return $this->legal_deliveries;
    }

    /**
     * Set area
     *
     * @param \Rg\ApiBundle\Entity\Area $area
     *
     * @return City
     */
    public function setArea(\Rg\ApiBundle\Entity\Area $area = null)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return \Rg\ApiBundle\Entity\Area
     */
    public function getArea()
    {
        return $this->area;
    }
}

