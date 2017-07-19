<?php

namespace Rg\ApiBundle\Entity;

/**
 * Product
 */
class Product
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
    private $description;

    /**
     * @var string
     */
    private $postal_index;

    /**
     * @var boolean
     */
    private $is_active;

    /**
     * @var boolean
     */
    private $is_subscribable;

    /**
     * @var integer
     */
    private $sort;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tariffs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $editions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tariffs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->editions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Product
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
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set postalIndex
     *
     * @param string $postalIndex
     *
     * @return Product
     */
    public function setPostalIndex($postalIndex)
    {
        $this->postal_index = $postalIndex;

        return $this;
    }

    /**
     * Get postalIndex
     *
     * @return string
     */
    public function getPostalIndex()
    {
        return $this->postal_index;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Product
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
     * Set isSubscribable
     *
     * @param boolean $isSubscribable
     *
     * @return Product
     */
    public function setIsSubscribable($isSubscribable)
    {
        $this->is_subscribable = $isSubscribable;

        return $this;
    }

    /**
     * Get isSubscribable
     *
     * @return boolean
     */
    public function getIsSubscribable()
    {
        return $this->is_subscribable;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return Product
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Add tariff
     *
     * @param \Rg\ApiBundle\Entity\Tariff $tariff
     *
     * @return Product
     */
    public function addTariff(\Rg\ApiBundle\Entity\Tariff $tariff)
    {
        $this->tariffs[] = $tariff;

        return $this;
    }

    /**
     * Remove tariff
     *
     * @param \Rg\ApiBundle\Entity\Tariff $tariff
     */
    public function removeTariff(\Rg\ApiBundle\Entity\Tariff $tariff)
    {
        $this->tariffs->removeElement($tariff);
    }

    /**
     * Get tariffs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTariffs()
    {
        return $this->tariffs;
    }

    /**
     * Add edition
     *
     * @param \Rg\ApiBundle\Entity\Edition $edition
     *
     * @return Product
     */
    public function addEdition(\Rg\ApiBundle\Entity\Edition $edition)
    {
        $this->editions[] = $edition;

        return $this;
    }

    /**
     * Remove edition
     *
     * @param \Rg\ApiBundle\Entity\Edition $edition
     */
    public function removeEdition(\Rg\ApiBundle\Entity\Edition $edition)
    {
        $this->editions->removeElement($edition);
    }

    /**
     * Get editions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEditions()
    {
        return $this->editions;
    }
    /**
     * @var boolean
     */
    private $is_kit;


    /**
     * Set isKit
     *
     * @param boolean $isKit
     *
     * @return Product
     */
    public function setIsKit($isKit)
    {
        $this->is_kit = $isKit;

        return $this;
    }

    /**
     * Get isKit
     *
     * @return boolean
     */
    public function getIsKit()
    {
        return $this->is_kit;
    }
    /**
     * @var boolean
     */
    private $is_archive;

    /**
     * @var boolean
     */
    private $is_outer;


    /**
     * Set isArchive
     *
     * @param boolean $isArchive
     *
     * @return Product
     */
    public function setIsArchive($isArchive)
    {
        $this->is_archive = $isArchive;

        return $this;
    }

    /**
     * Get isArchive
     *
     * @return boolean
     */
    public function getIsArchive()
    {
        return $this->is_archive;
    }

    /**
     * Set isOuter
     *
     * @param boolean $isOuter
     *
     * @return Product
     */
    public function setIsOuter($isOuter)
    {
        $this->is_outer = $isOuter;

        return $this;
    }

    /**
     * Get isOuter
     *
     * @return boolean
     */
    public function getIsOuter()
    {
        return $this->is_outer;
    }
}
