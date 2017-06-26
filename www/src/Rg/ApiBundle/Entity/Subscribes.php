<?php

namespace Rg\ApiBundle\Entity;

/**
 * Subscribes
 */
class Subscribes
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $namePeriodId;

    /**
     * @var int
     */
    private $areaId;

    /**
     * @var \DateTime
     */
    private $subscribePeriodStart;

    /**
     * @var \DateTime
     */
    private $subscribePeriodEnd;

    /**
     * @var int
     */
    private $periodId;


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
     * Set namePeriodId
     *
     * @param integer $namePeriodId
     *
     * @return Subscribes
     */
    public function setNamePeriodId($namePeriodId)
    {
        $this->namePeriodId = $namePeriodId;

        return $this;
    }

    /**
     * Get namePeriodId
     *
     * @return int
     */
    public function getNamePeriodId()
    {
        return $this->namePeriodId;
    }

    /**
     * Set areaId
     *
     * @param integer $areaId
     *
     * @return Subscribes
     */
    public function setAreaId($areaId)
    {
        $this->areaId = $areaId;

        return $this;
    }

    /**
     * Get areaId
     *
     * @return int
     */
    public function getAreaId()
    {
        return $this->areaId;
    }

    /**
     * Set subscribePeriodStart
     *
     * @param \DateTime $subscribePeriodStart
     *
     * @return Subscribes
     */
    public function setSubscribePeriodStart($subscribePeriodStart)
    {
        $this->subscribePeriodStart = $subscribePeriodStart;

        return $this;
    }

    /**
     * Get subscribePeriodStart
     *
     * @return \DateTime
     */
    public function getSubscribePeriodStart()
    {
        return $this->subscribePeriodStart;
    }

    /**
     * Set subscribePeriodEnd
     *
     * @param \DateTime $subscribePeriodEnd
     *
     * @return Subscribes
     */
    public function setSubscribePeriodEnd($subscribePeriodEnd)
    {
        $this->subscribePeriodEnd = $subscribePeriodEnd;

        return $this;
    }

    /**
     * Get subscribePeriodEnd
     *
     * @return \DateTime
     */
    public function getSubscribePeriodEnd()
    {
        return $this->subscribePeriodEnd;
    }

    /**
     * Set periodId
     *
     * @param integer $periodId
     *
     * @return Subscribes
     */
    public function setPeriodId($periodId)
    {
        $this->periodId = $periodId;

        return $this;
    }

    /**
     * Get periodId
     *
     * @return int
     */
    public function getPeriodId()
    {
        return $this->periodId;
    }
}

