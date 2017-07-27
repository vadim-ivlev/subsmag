<?php

namespace Rg\ApiBundle\Entity;

/**
 * Period
 */
class Period
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
    private $year;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tariffs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $intervals;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tariffs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->intervals = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Period
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
     * Set year
     *
     * @param integer $year
     *
     * @return Period
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
     * @return Period
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
     * Add interval
     *
     * @param \Rg\ApiBundle\Entity\Interval $interval
     *
     * @return Period
     */
    public function addInterval(\Rg\ApiBundle\Entity\Interval $interval)
    {
        $this->intervals[] = $interval;

        return $this;
    }

    /**
     * Remove interval
     *
     * @param \Rg\ApiBundle\Entity\Interval $interval
     */
    public function removeInterval(\Rg\ApiBundle\Entity\Interval $interval)
    {
        $this->intervals->removeElement($interval);
    }

    /**
     * Get intervals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIntervals()
    {
        return $this->intervals;
    }
    /**
     * @var integer
     */
    private $first_month;

    /**
     * @var integer
     */
    private $duration;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $goods;


    /**
     * Set firstMonth
     *
     * @param integer $firstMonth
     *
     * @return Period
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
     * @return Period
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
     * Add good
     *
     * @param \Rg\ApiBundle\Entity\Good $good
     *
     * @return Period
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
}
