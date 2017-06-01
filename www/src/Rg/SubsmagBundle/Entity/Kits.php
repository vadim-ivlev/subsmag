<?php

namespace Rg\SubsmagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Kits
 */
class Kits
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nameKit;

    /**
     * @var bool
     */
    private $flagSubscribe;


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
     * Set nameKit
     *
     * @param string $nameKit
     *
     * @return Kit
     */
    public function setNameKit($nameKit)
    {
        $this->nameKit = $nameKit;

        return $this;
    }

    /**
     * Get nameKit
     *
     * @return string
     */
    public function getNameKit()
    {
        return $this->nameKit;
    }

    /**
     * Set flagSubscribe
     *
     * @param boolean $flagSubscribe
     *
     * @return Kit
     */
    public function setFlagSubscribe($flagSubscribe)
    {
        $this->flagSubscribe = $flagSubscribe;

        return $this;
    }

    /**
     * Get flagSubscribe
     *
     * @return bool
     */
    public function getFlagSubscribe()
    {
        return $this->flagSubscribe;
    }
}
