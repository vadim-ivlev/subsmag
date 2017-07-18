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
     * @var integer
     */
    private $month_start;

    /**
     * @var integer
     */
    private $year_start;

    /**
     * @var integer
     */
    private $duration;

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
     * Set monthStart
     *
     * @param integer $monthStart
     *
     * @return Period
     */
    public function setMonthStart($monthStart)
    {
        $this->month_start = $monthStart;

        return $this;
    }

    /**
     * Get monthStart
     *
     * @return integer
     */
    public function getMonthStart()
    {
        return $this->month_start;
    }

    /**
     * Set yearStart
     *
     * @param integer $yearStart
     *
     * @return Period
     */
    public function setYearStart($yearStart)
    {
        $this->year_start = $yearStart;

        return $this;
    }

    /**
     * Get yearStart
     *
     * @return integer
     */
    public function getYearStart()
    {
        return $this->year_start;
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
}

