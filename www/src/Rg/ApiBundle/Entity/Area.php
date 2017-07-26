<?php

namespace Rg\ApiBundle\Entity;

/**
 * Area
 */
class Area
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Rg\ApiBundle\Entity\Zone
     */
    private $zone;


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
     * Set name
     *
     * @param string $name
     *
     * @return Area
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set zone
     *
     * @param \Rg\ApiBundle\Entity\Zone $zone
     *
     * @return Area
     */
    public function setZone(\Rg\ApiBundle\Entity\Zone $zone = null)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return \Rg\ApiBundle\Entity\Zone
     */
    public function getZone()
    {
        return $this->zone;
    }
    /**
     * @var integer
     */
    private $from_front_id;


    /**
     * Set fromFrontId
     *
     * @param integer $fromFrontId
     *
     * @return Area
     */
    public function setFromFrontId($fromFrontId)
    {
        $this->from_front_id = $fromFrontId;

        return $this;
    }

    /**
     * Get fromFrontId
     *
     * @return integer
     */
    public function getFromFrontId()
    {
        return $this->from_front_id;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $intervals;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->intervals = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add interval
     *
     * @param \Rg\ApiBundle\Entity\Interval $interval
     *
     * @return Area
     */
    public function addInterval(\Rg\ApiBundle\Entity\Interval $interval)
    {
        $this->intervals[] = $interval;

        return $this;
    }

    /**
     * Remove interval
     *
     * @param \Rg\ApiBundle\Entity\Interval $interval
     */
    public function removeInterval(\Rg\ApiBundle\Entity\Interval $interval)
    {
        $this->intervals->removeElement($interval);
    }

    /**
     * Get intervals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIntervals()
    {
        return $this->intervals;
    }
}
