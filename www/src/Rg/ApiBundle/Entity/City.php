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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $legals;

    /**
     * @var \Rg\ApiBundle\Entity\Area
     */
    private $area;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->legals = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add legal
     *
     * @param \Rg\ApiBundle\Entity\Legal $legal
     *
     * @return City
     */
    public function addLegal(\Rg\ApiBundle\Entity\Legal $legal)
    {
        $this->legals[] = $legal;

        return $this;
    }

    /**
     * Remove legal
     *
     * @param \Rg\ApiBundle\Entity\Legal $legal
     */
    public function removeLegal(\Rg\ApiBundle\Entity\Legal $legal)
    {
        $this->legals->removeElement($legal);
    }

    /**
     * Get legals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLegals()
    {
        return $this->legals;
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
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $legal_addresses;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $legal_deliveries;


    /**
     * Add legalAddress
     *
     * @param \Rg\ApiBundle\Entity\Legal $legalAddress
     *
     * @return City
     */
    public function addLegalAddress(\Rg\ApiBundle\Entity\Legal $legalAddress)
    {
        $this->legal_addresses[] = $legalAddress;

        return $this;
    }

    /**
     * Remove legalAddress
     *
     * @param \Rg\ApiBundle\Entity\Legal $legalAddress
     */
    public function removeLegalAddress(\Rg\ApiBundle\Entity\Legal $legalAddress)
    {
        $this->legal_addresses->removeElement($legalAddress);
    }

    /**
     * Get legalAddresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLegalAddresses()
    {
        return $this->legal_addresses;
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
}
