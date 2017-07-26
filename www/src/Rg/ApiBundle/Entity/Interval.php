<?php

namespace Rg\ApiBundle\Entity;

/**
 * Interval
 */
class Interval
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @var boolean
     */
    private $is_moscow;

    /**
     * @var \Rg\ApiBundle\Entity\Period
     */
    private $period;


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
     * Set start
     *
     * @param \DateTime $start
     *
     * @return Interval
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     *
     * @return Interval
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set isMoscow
     *
     * @param boolean $isMoscow
     *
     * @return Interval
     */
    public function setIsMoscow($isMoscow)
    {
        $this->is_moscow = $isMoscow;

        return $this;
    }

    /**
     * Get isMoscow
     *
     * @return boolean
     */
    public function getIsMoscow()
    {
        return $this->is_moscow;
    }

    /**
     * Set period
     *
     * @param \Rg\ApiBundle\Entity\Period $period
     *
     * @return Interval
     */
    public function setPeriod(\Rg\ApiBundle\Entity\Period $period = null)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get period
     *
     * @return \Rg\ApiBundle\Entity\Period
     */
    public function getPeriod()
    {
        return $this->period;
    }
    /**
     * @var boolean
     */
    private $is_regional;

    /**
     * @var \Rg\ApiBundle\Entity\Timeblock
     */
    private $timeblock;

    /**
     * @var \Rg\ApiBundle\Entity\Area
     */
    private $area;


    /**
     * Set isRegional
     *
     * @param boolean $isRegional
     *
     * @return Interval
     */
    public function setIsRegional($isRegional)
    {
        $this->is_regional = $isRegional;

        return $this;
    }

    /**
     * Get isRegional
     *
     * @return boolean
     */
    public function getIsRegional()
    {
        return $this->is_regional;
    }

    /**
     * Set timeblock
     *
     * @param \Rg\ApiBundle\Entity\Timeblock $timeblock
     *
     * @return Interval
     */
    public function setTimeblock(\Rg\ApiBundle\Entity\Timeblock $timeblock = null)
    {
        $this->timeblock = $timeblock;

        return $this;
    }

    /**
     * Get timeblock
     *
     * @return \Rg\ApiBundle\Entity\Timeblock
     */
    public function getTimeblock()
    {
        return $this->timeblock;
    }

    /**
     * Set area
     *
     * @param \Rg\ApiBundle\Entity\Area $area
     *
     * @return Interval
     */
    public function setArea(\Rg\ApiBundle\Entity\Area $area = null)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return \Rg\ApiBundle\Entity\Area
     */
    public function getArea()
    {
        return $this->area;
    }
}
