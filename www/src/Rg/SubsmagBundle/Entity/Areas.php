<?php

namespace Rg\SubsmagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Areas
 */
class Areas
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $areaName;


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
     * Set areaName
     *
     * @param string $areaName
     *
     * @return Areas
     */
    public function setAreaName($areaName)
    {
        $this->areaName = $areaName;

        return $this;
    }

    /**
     * Get areaName
     *
     * @return string
     */
    public function getAreaName()
    {
        return $this->areaName;
    }
}
