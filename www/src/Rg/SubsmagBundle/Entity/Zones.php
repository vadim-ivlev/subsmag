<?php

namespace Rg\SubsmagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @param string $tarifId
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
     * @return string
     */ 
    public function getTarifId()
    {
        return $this->tarifId;
    }
}
