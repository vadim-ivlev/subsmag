<?php

namespace Rg\ApiBundle\Entity;

/**
 * Timeunit
 */
class Timeunit
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
    private $bitmask;

    /**
     * @var integer
     */
    private $first_month;

    /**
     * @var integer
     */
    private $duration;

    /**
     * @var integer
     */
    private $year;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tariffs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $promos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tariffs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->promos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Timeunit
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
     * Set bitmask
     *
     * @param integer $bitmask
     *
     * @return Timeunit
     */
    public function setBitmask($bitmask)
    {
        $this->bitmask = $bitmask;

        return $this;
    }

    /**
     * Get bitmask
     *
     * @return integer
     */
    public function getBitmask()
    {
        return $this->bitmask;
    }

    /**
     * Set firstMonth
     *
     * @param integer $firstMonth
     *
     * @return Timeunit
     */
    public function setFirstMonth($firstMonth)
    {
        $this->first_month = $firstMonth;

        return $this;
    }

    /**
     * Get firstMonth
     *
     * @return integer
     */
    public function getFirstMonth()
    {
        return $this->first_month;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Timeunit
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
     * Set year
     *
     * @param integer $year
     *
     * @return Timeunit
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Add tariff
     *
     * @param \Rg\ApiBundle\Entity\Tariff $tariff
     *
     * @return Timeunit
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
     * Add promo
     *
     * @param \Rg\ApiBundle\Entity\Promo $promo
     *
     * @return Timeunit
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

