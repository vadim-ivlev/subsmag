<?php

namespace Rg\ApiBundle\Entity;

/**
 * Actions
 */
class Actions
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $introtext;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $dateStart;

    /**
     * @var \DateTime
     */
    private $dateEnd;

    /**
     * @var int
     */
    private $discount;

    /**
     * @var string
     */
    private $giftDescription;

    /**
     * @var bool
     */
    private $flagVisibleOnSite;

    /**
     * @var bool
     */
    private $flagPercentOrFix;

    /**
     * @var int
     */
    private $cntUsed;

    /**
     * @var int
     */
    private $productId;

    /**
     * @var int
     */
    private $kitId;

    /**
     * @var int
     */
    private $promocodeId;

    /**
     * @var int
     */
    private $userId;


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
     * Set name
     *
     * @param string $name
     *
     * @return Actions
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
     * Set introtext
     *
     * @param string $introtext
     *
     * @return Actions
     */
    public function setIntrotext($introtext)
    {
        $this->introtext = $introtext;

        return $this;
    }

    /**
     * Get introtext
     *
     * @return string
     */
    public function getIntrotext()
    {
        return $this->introtext;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Actions
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

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     *
     * @return Actions
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
     * @return Actions
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
     * Set discount
     *
     * @param integer $discount
     *
     * @return Actions
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return int
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set giftDescription
     *
     * @param string $giftDescription
     *
     * @return Actions
     */
    public function setGiftDescription($giftDescription)
    {
        $this->giftDescription = $giftDescription;

        return $this;
    }

    /**
     * Get giftDescription
     *
     * @return string
     */
    public function getGiftDescription()
    {
        return $this->giftDescription;
    }

    /**
     * Set flagVisibleOnSite
     *
     * @param boolean $flagVisibleOnSite
     *
     * @return Actions
     */
    public function setFlagVisibleOnSite($flagVisibleOnSite)
    {
        $this->flagVisibleOnSite = $flagVisibleOnSite;

        return $this;
    }

    /**
     * Get flagVisibleOnSite
     *
     * @return bool
     */
    public function getFlagVisibleOnSite()
    {
        return $this->flagVisibleOnSite;
    }

    /**
     * Set flagPercentOrFix
     *
     * @param boolean $flagPercentOrFix
     *
     * @return Actions
     */
    public function setFlagPercentOrFix($flagPercentOrFix)
    {
        $this->flagPercentOrFix = $flagPercentOrFix;

        return $this;
    }

    /**
     * Get flagPercentOrFix
     *
     * @return bool
     */
    public function getFlagPercentOrFix()
    {
        return $this->flagPercentOrFix;
    }

    /**
     * Set cntUsed
     *
     * @param integer $cntUsed
     *
     * @return Actions
     */
    public function setCntUsed($cntUsed)
    {
        $this->cntUsed = $cntUsed;

        return $this;
    }

    /**
     * Get cntUsed
     *
     * @return int
     */
    public function getCntUsed()
    {
        return $this->cntUsed;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     *
     * @return Actions
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set kitId
     *
     * @param integer $kitId
     *
     * @return Actions
     */
    public function setKitId($kitId)
    {
        $this->kitId = $kitId;

        return $this;
    }

    /**
     * Get kitId
     *
     * @return int
     */
    public function getKitId()
    {
        return $this->kitId;
    }

    /**
     * Set promocodeId
     *
     * @param integer $promocodeId
     *
     * @return Actions
     */
    public function setPromocodeId($promocodeId)
    {
        $this->promocodeId = $promocodeId;

        return $this;
    }

    /**
     * Get promocodeId
     *
     * @return int
     */
    public function getPromocodeId()
    {
        return $this->promocodeId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Actions
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
