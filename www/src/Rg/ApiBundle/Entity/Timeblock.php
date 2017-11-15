<?php

namespace Rg\ApiBundle\Entity;

/**
 * Timeblock
 */
class Timeblock
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
    private $postal_indexes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postal_indexes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Timeblock
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
     * Add postalIndex
     *
     * @param \Rg\ApiBundle\Entity\PostalIndex $postalIndex
     *
     * @return Timeblock
     */
    public function addPostalIndex(\Rg\ApiBundle\Entity\PostalIndex $postalIndex)
    {
        $this->postal_indexes[] = $postalIndex;

        return $this;
    }

    /**
     * Remove postalIndex
     *
     * @param \Rg\ApiBundle\Entity\PostalIndex $postalIndex
     */
    public function removePostalIndex(\Rg\ApiBundle\Entity\PostalIndex $postalIndex)
    {
        $this->postal_indexes->removeElement($postalIndex);
    }

    /**
     * Get postalIndexes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPostalIndexes()
    {
        return $this->postal_indexes;
    }
    /**
     * @var integer
     */
    private $duration;


    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Timeblock
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $promos;


    /**
     * Add promo
     *
     * @param \Rg\ApiBundle\Entity\Promo $promo
     *
     * @return Timeblock
     */
    public function addPromo(\Rg\ApiBundle\Entity\Promo $promo)
    {
        $this->promos[] = $promo;

        return $this;
    }

    /**
     * Remove promo
     *
     * @param \Rg\ApiBundle\Entity\Promo $promo
     */
    public function removePromo(\Rg\ApiBundle\Entity\Promo $promo)
    {
        $this->promos->removeElement($promo);
    }

    /**
     * Get promos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPromos()
    {
        return $this->promos;
    }
}
