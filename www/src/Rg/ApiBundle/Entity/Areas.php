<?php

namespace Rg\ApiBundle\Entity;

/**
 * Areas
 */
class Areas
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nameArea;


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
