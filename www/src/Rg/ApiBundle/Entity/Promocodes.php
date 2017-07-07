<?php

namespace Rg\ApiBundle\Entity;

/**
 * Promocodes
 */
class Promocodes
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var \DateTime
     */
    private $dateStart;

    /**
     * @var \DateTime
     */
    private $dateEnd;

    /**
     * @var bool
     */
    private $flagUsed;

    /**
     * @var int
     */
    private $actionId;


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
     * Set code
     *
     * @param string $code
     *
     * @return Promocodes
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     *
     * @return Promocodes
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     *
     * @return Promocodes
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set flagUsed
     *
     * @param boolean $flagUsed
     *
     * @return Promocodes
     */
    public function setFlagUsed($flagUsed)
    {
        $this->flagUsed = $flagUsed;

        return $this;
    }

    /**
     * Get flagUsed
     *
     * @return bool
     */
    public function getFlagUsed()
    {
        return $this->flagUsed;
    }

    /**
     * Set actionId
     *
     * @param integer $actionId
     *
     * @return Promocodes
     */
    public function setActionId($actionId)
    {
        $this->actionId = $actionId;

        return $this;
    }

    /**
     * Get actionId
     *
     * @return integer
     */
    public function getActionId()
    {
        return $this->actionId;
    }
}
