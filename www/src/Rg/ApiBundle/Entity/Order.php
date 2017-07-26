<?php

namespace Rg\ApiBundle\Entity;

/**
 * Order
 */
class Order
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $address;

    /**
     * @var integer
     */
    private $start_month;

    /**
     * @var integer
     */
    private $duration;

    /**
     * @var float
     */
    private $sum;

    /**
     * @var boolean
     */
    private $is_paid;

    /**
     * @var \Rg\ApiBundle\Entity\Payment
     */
    private $payment;

    /**
     * @var \Rg\ApiBundle\Entity\User
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tariffs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tariffs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Order
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Order
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set startMonth
     *
     * @param integer $startMonth
     *
     * @return Order
     */
    public function setStartMonth($startMonth)
    {
        $this->start_month = $startMonth;

        return $this;
    }

    /**
     * Get startMonth
     *
     * @return integer
     */
    public function getStartMonth()
    {
        return $this->start_month;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Order
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
     * Set sum
     *
     * @param float $sum
     *
     * @return Order
     */
    public function setSum($sum)
    {
        $this->sum = $sum;

        return $this;
    }

    /**
     * Get sum
     *
     * @return float
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Set isPaid
     *
     * @param boolean $isPaid
     *
     * @return Order
     */
    public function setIsPaid($isPaid)
    {
        $this->is_paid = $isPaid;

        return $this;
    }

    /**
     * Get isPaid
     *
     * @return boolean
     */
    public function getIsPaid()
    {
        return $this->is_paid;
    }

    /**
     * Set payment
     *
     * @param \Rg\ApiBundle\Entity\Payment $payment
     *
     * @return Order
     */
    public function setPayment(\Rg\ApiBundle\Entity\Payment $payment = null)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return \Rg\ApiBundle\Entity\Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set user
     *
     * @param \Rg\ApiBundle\Entity\User $user
     *
     * @return Order
     */
    public function setUser(\Rg\ApiBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Rg\ApiBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add tariff
     *
     * @param \Rg\ApiBundle\Entity\Tariff $tariff
     *
     * @return Order
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $timeblocks;


    /**
     * Add timeblock
     *
     * @param \Rg\ApiBundle\Entity\Timeblock $timeblock
     *
     * @return Order
     */
    public function addTimeblock(\Rg\ApiBundle\Entity\Timeblock $timeblock)
    {
        $this->timeblocks[] = $timeblock;

        return $this;
    }

    /**
     * Remove timeblock
     *
     * @param \Rg\ApiBundle\Entity\Timeblock $timeblock
     */
    public function removeTimeblock(\Rg\ApiBundle\Entity\Timeblock $timeblock)
    {
        $this->timeblocks->removeElement($timeblock);
    }

    /**
     * Get timeblocks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTimeblocks()
    {
        return $this->timeblocks;
    }
}
