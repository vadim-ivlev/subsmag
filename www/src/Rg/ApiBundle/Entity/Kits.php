<?php

namespace Rg\ApiBundle\Entity;

use Doctrine\ORM\EntityRepository;

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
     * @return Kits
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
     * @return Kits
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

