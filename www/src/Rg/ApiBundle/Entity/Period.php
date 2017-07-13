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
}
