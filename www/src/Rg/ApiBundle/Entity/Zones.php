<?php

namespace Rg\ApiBundle\Entity;

/**
 * Zones
 */
class Zones
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $zoneNumber;

    /**
     * @var integer
     */
    private $areaId;

    /**
     * @var integer
     */
    private $tarifId;


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
     * Set zoneNumber
     *
     * @param integer $zoneNumber
     *
     * @return Zones
     */
    public function setZoneNumber($zoneNumber)
    {
        $this->zoneNumber = $zoneNumber;

        return $this;
    }

    /**
     * Get zoneNumber
     *
     * @return integer
     */
    public function getZoneNumber()
    {
        return $this->zoneNumber;
    }

    /**
     * Set areaId
     *
     * @param integer $areaId
     *
     * @return Zones
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
     * Set tarifId
     *
     * @param integer $tarifId
     *
     * @return Zones
     */
    public function setTarifId($tarifId)
    {
        $this->tarifId = $tarifId;

        return $this;
    }

    /**
     * Get tarifId
     *
     * @return integer
     */
    public function getTarifId()
    {
        return $this->tarifId;
    }
}
