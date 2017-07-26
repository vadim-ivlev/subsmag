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
     * @var integer
     */
    private $decimal_view;

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
    private $orders;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tariffs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set decimalView
     *
     * @param integer $decimalView
     *
     * @return Timeblock
     */
    public function setDecimalView($decimalView)
    {
        $this->decimal_view = $decimalView;

        return $this;
    }

    /**
     * Get decimalView
     *
     * @return integer
     */
    public function getDecimalView()
    {
        return $this->decimal_view;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Timeblock
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
     * @return Timeblock
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
     * Add order
     *
     * @param \Rg\ApiBundle\Entity\Order $order
     *
     * @return Timeblock
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $intervals;


    /**
     * Add interval
     *
     * @param \Rg\ApiBundle\Entity\Interval $interval
     *
     * @return Timeblock
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
     * Set firstMonth
     *
     * @param integer $firstMonth
     *
     * @return Timeblock
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
}
