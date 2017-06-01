<?php

namespace Rg\SubsmagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Periods
 */
class Periods
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $monthStart;

    /**
     * @var int
     */
    private $yearStart;

    /**
     * @var int
     */
    private $periodMonths;

    /**
     * @var int
     */
    private $quantityMonthsStart;

    /**
     * @var int
     */
    private $quantityMonthsEnd;


    /**
     * Get id
     *
     * @return int
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
     * @return int
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
     * @return int
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
     * @return int
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
     * @return int
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
     * @return int
     */
    public function getQuantityMonthsEnd()
    {
        return $this->quantityMonthsEnd;
    }
}
