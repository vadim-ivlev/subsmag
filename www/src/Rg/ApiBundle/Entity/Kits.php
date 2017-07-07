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
     * @var string
     */
    private $image;

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }


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
    /**
     * @var integer
     */
    private $sort;


    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return Kits
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
    }
    /**
     * @var string
     */
    private $description;


    /**
     * Set description
     *
     * @param string $description
     *
     * @return Kits
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
