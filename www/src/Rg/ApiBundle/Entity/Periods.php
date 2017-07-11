<?php

namespace Rg\ApiBundle\Entity;

/**
 * Periods
 */
class Periods
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $monthStart;

    /**
     * @var integer
     */
    private $yearStart;

    /**
     * @var integer
     */
    private $periodMonths;

    /**
     * @var integer
     */
    private $quantityMonthsStart;

    /**
     * @var integer
     */
    private $quantityMonthsEnd;

    /**
     * @var integer
     */
    private $subscribeId;


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
     * @return Periods
     */
    public function setMonthStart($monthStart)
    {
        $this->monthStart = $monthStart;

        return $this;
    }

    /**
     * Get monthStart
     *
     * @return integer
     */
    public function getMonthStart()
    {
        return $this->monthStart;
    }

    /**
     * Set yearStart
     *
     * @param integer $yearStart
     *
     * @return Periods
     */
    public function setYearStart($yearStart)
    {
        $this->yearStart = $yearStart;

        return $this;
    }

    /**
     * Get yearStart
     *
     * @return integer
     */
    public function getYearStart()
    {
        return $this->yearStart;
    }

    /**
     * Set periodMonths
     *
     * @param integer $periodMonths
     *
     * @return Periods
     */
    public function setPeriodMonths($periodMonths)
    {
        $this->periodMonths = $periodMonths;

        return $this;
    }

    /**
     * Get periodMonths
     *
     * @return integer
     */
    public function getPeriodMonths()
    {
        return $this->periodMonths;
    }

    /**
     * Set quantityMonthsStart
     *
     * @param integer $quantityMonthsStart
     *
     * @return Periods
     */
    public function setQuantityMonthsStart($quantityMonthsStart)
    {
        $this->quantityMonthsStart = $quantityMonthsStart;

        return $this;
    }

    /**
     * Get quantityMonthsStart
     *
     * @return integer
     */
    public function getQuantityMonthsStart()
    {
        return $this->quantityMonthsStart;
    }

    /**
     * Set quantityMonthsEnd
     *
     * @param integer $quantityMonthsEnd
     *
     * @return Periods
     */
    public function setQuantityMonthsEnd($quantityMonthsEnd)
    {
        $this->quantityMonthsEnd = $quantityMonthsEnd;

        return $this;
    }

    /**
     * Get quantityMonthsEnd
     *
     * @return integer
     */
    public function getQuantityMonthsEnd()
    {
        return $this->quantityMonthsEnd;
    }

    /**
     * Set subscribeId
     *
     * @param integer $subscribeId
     *
     * @return Periods
     */
    public function setSubscribeId($subscribeId)
    {
        $this->subscribeId = $subscribeId;

        return $this;
    }

    /**
     * Get subscribeId
     *
     * @return integer
     */
    public function getSubscribeId()
    {
        return $this->subscribeId;
    }
}
