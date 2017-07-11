<?php

namespace Rg\ApiBundle\Entity;

/**
 * Actions
 */
class Actions
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
     * @var integer
     */
    private $discount;

    /**
     * @var string
     */
    private $giftDescription;

    /**
     * @var boolean
     */
    private $flagVisibleOnSite;

    /**
     * @var integer
     */
    private $flagPercentOrFix;

    /**
     * @var integer
     */
    private $cntUsed;

    /**
     * @var integer
     */
    private $productId;

    /**
     * @var integer
     */
    private $kitId;

    /**
     * @var integer
     */
    private $promocodeId;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $kits;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->kits = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return integer
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
     * @return boolean
     */
    public function getFlagVisibleOnSite()
    {
        return $this->flagVisibleOnSite;
    }

    /**
     * Set flagPercentOrFix
     *
     * @param integer $flagPercentOrFix
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
     * @return integer
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
     * @return integer
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
     * @return integer
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
     * @return integer
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
     * @return integer
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
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Add kit
     *
     * @param \Rg\ApiBundle\Entity\Kits $kit
     *
     * @return Actions
     */
    public function addKit(\Rg\ApiBundle\Entity\Kits $kit)
    {
        $this->kits[] = $kit;

        return $this;
    }

    /**
     * Remove kit
     *
     * @param \Rg\ApiBundle\Entity\Kits $kit
     */
    public function removeKit(\Rg\ApiBundle\Entity\Kits $kit)
    {
        $this->kits->removeElement($kit);
    }

    /**
     * Get kits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getKits()
    {
        return $this->kits;
    }
}
