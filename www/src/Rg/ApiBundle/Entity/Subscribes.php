<?php

namespace Rg\ApiBundle\Entity;

/**
 * Subscribes
 */
class Subscribes
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $namePeriodId;

    /**
     * @var integer
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
     * @var integer
     */
    private $productId;

    /**
     * @var integer
     */
    private $kitId;


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
     * @return integer
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
     * @return integer
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
     * Set productId
     *
     * @param integer $productId
     *
     * @return Subscribes
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set kitId
     *
     * @param integer $kitId
     *
     * @return Subscribes
     */
    public function setKitId($kitId)
    {
        $this->kitId = $kitId;

        return $this;
    }

    /**
     * Get kitId
     *
     * @return integer
     */
    public function getKitId()
    {
        return $this->kitId;
    }
}
