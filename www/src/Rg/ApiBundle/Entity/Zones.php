<?php

namespace Rg\ApiBundle\Entity;

/**
 * Zones
 */
class Zones
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $zoneNumber;

    /**
     * @var int
     */
    private $areaId;

    /**
     * @return int
     */
    public function getAreaId()
    {
        return $this->areaId;
    }

    /**
     * @param int $areaId
     */
    public function setAreaId($areaId)
    {
        $this->areaId = $areaId;
    }

    /**
     * @var int
     */
    private $tarifId;


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
     * @return int
     */
    public function getZoneNumber()
    {
        return $this->zoneNumber;
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
     * @return int
     */
    public function getTarifId()
    {
        return $this->tarifId;
    }
}
