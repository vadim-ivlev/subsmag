<?php

namespace Rg\ApiBundle\Entity;

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
    private $nameArea;


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
     * Set nameArea
     *
     * @param string $nameArea
     *
     * @return Areas
     */
    public function setNameArea($nameArea)
    {
        $this->nameArea = $nameArea;

        return $this;
    }

    /**
     * Get nameArea
     *
     * @return string
     */
    public function getNameArea()
    {
        return $this->nameArea;
    }
}

