<?php

namespace Rg\ApiBundle\Entity;

/**
 * Promo
 */
class Promo
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
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @var boolean
     */
    private $is_active;

    /**
     * @var string
     */
    private $code;

    /**
     * @var integer
     */
    private $amount;

    /**
     * @var integer
     */
    private $sold;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $image;

    /**
     * @var boolean
     */
    private $is_alert;

    /**
     * @var boolean
     */
    private $is_visible;

    /**
     * @var boolean
     */
    private $is_form;

    /**
     * @var string
     */
    private $document;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text2;

    /**
     * @var string
     */
    private $text3;

    /**
     * @var string
     */
    private $conditions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $items;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $pins;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $discounts;

    /**
     * @var \Rg\ApiBundle\Entity\Timeunit
     */
    private $timeunit;

    /**
     * @var \Rg\ApiBundle\Entity\Area
     */
    private $area;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $zones;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pins = new \Doctrine\Common\Collections\ArrayCollection();
        $this->discounts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->zones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Promo
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
     * Set start
     *
     * @param \DateTime $start
     *
     * @return Promo
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     *
     * @return Promo
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Promo
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Promo
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
     * Set amount
     *
     * @param integer $amount
     *
     * @return Promo
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set sold
     *
     * @param integer $sold
     *
     * @return Promo
     */
    public function setSold($sold)
    {
        $this->sold = $sold;

        return $this;
    }

    /**
     * Get sold
     *
     * @return integer
     */
    public function getSold()
    {
        return $this->sold;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Promo
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
     * Set image
     *
     * @param string $image
     *
     * @return Promo
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set isAlert
     *
     * @param boolean $isAlert
     *
     * @return Promo
     */
    public function setIsAlert($isAlert)
    {
        $this->is_alert = $isAlert;

        return $this;
    }

    /**
     * Get isAlert
     *
     * @return boolean
     */
    public function getIsAlert()
    {
        return $this->is_alert;
    }

    /**
     * Set isVisible
     *
     * @param boolean $isVisible
     *
     * @return Promo
     */
    public function setIsVisible($isVisible)
    {
        $this->is_visible = $isVisible;

        return $this;
    }

    /**
     * Get isVisible
     *
     * @return boolean
     */
    public function getIsVisible()
    {
        return $this->is_visible;
    }

    /**
     * Set isForm
     *
     * @param boolean $isForm
     *
     * @return Promo
     */
    public function setIsForm($isForm)
    {
        $this->is_form = $isForm;

        return $this;
    }

    /**
     * Get isForm
     *
     * @return boolean
     */
    public function getIsForm()
    {
        return $this->is_form;
    }

    /**
     * Set document
     *
     * @param string $document
     *
     * @return Promo
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Promo
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text2
     *
     * @param string $text2
     *
     * @return Promo
     */
    public function setText2($text2)
    {
        $this->text2 = $text2;

        return $this;
    }

    /**
     * Get text2
     *
     * @return string
     */
    public function getText2()
    {
        return $this->text2;
    }

    /**
     * Set text3
     *
     * @param string $text3
     *
     * @return Promo
     */
    public function setText3($text3)
    {
        $this->text3 = $text3;

        return $this;
    }

    /**
     * Get text3
     *
     * @return string
     */
    public function getText3()
    {
        return $this->text3;
    }

    /**
     * Set conditions
     *
     * @param string $conditions
     *
     * @return Promo
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;

        return $this;
    }

    /**
     * Get conditions
     *
     * @return string
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * Add item
     *
     * @param \Rg\ApiBundle\Entity\Item $item
     *
     * @return Promo
     */
    public function addItem(\Rg\ApiBundle\Entity\Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \Rg\ApiBundle\Entity\Item $item
     */
    public function removeItem(\Rg\ApiBundle\Entity\Item $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add pin
     *
     * @param \Rg\ApiBundle\Entity\Pin $pin
     *
     * @return Promo
     */
    public function addPin(\Rg\ApiBundle\Entity\Pin $pin)
    {
        $this->pins[] = $pin;

        return $this;
    }

    /**
     * Remove pin
     *
     * @param \Rg\ApiBundle\Entity\Pin $pin
     */
    public function removePin(\Rg\ApiBundle\Entity\Pin $pin)
    {
        $this->pins->removeElement($pin);
    }

    /**
     * Get pins
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPins()
    {
        return $this->pins;
    }

    /**
     * Add discount
     *
     * @param \Rg\ApiBundle\Entity\Discount $discount
     *
     * @return Promo
     */
    public function addDiscount(\Rg\ApiBundle\Entity\Discount $discount)
    {
        $this->discounts[] = $discount;

        return $this;
    }

    /**
     * Remove discount
     *
     * @param \Rg\ApiBundle\Entity\Discount $discount
     */
    public function removeDiscount(\Rg\ApiBundle\Entity\Discount $discount)
    {
        $this->discounts->removeElement($discount);
    }

    /**
     * Get discounts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDiscounts()
    {
        return $this->discounts;
    }

    /**
     * Set timeunit
     *
     * @param \Rg\ApiBundle\Entity\Timeunit $timeunit
     *
     * @return Promo
     */
    public function setTimeunit(\Rg\ApiBundle\Entity\Timeunit $timeunit = null)
    {
        $this->timeunit = $timeunit;

        return $this;
    }

    /**
     * Get timeunit
     *
     * @return \Rg\ApiBundle\Entity\Timeunit
     */
    public function getTimeunit()
    {
        return $this->timeunit;
    }

    /**
     * Set area
     *
     * @param \Rg\ApiBundle\Entity\Area $area
     *
     * @return Promo
     */
    public function setArea(\Rg\ApiBundle\Entity\Area $area = null)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return \Rg\ApiBundle\Entity\Area
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Add zone
     *
     * @param \Rg\ApiBundle\Entity\Zone $zone
     *
     * @return Promo
     */
    public function addZone(\Rg\ApiBundle\Entity\Zone $zone)
    {
        $this->zones[] = $zone;

        return $this;
    }

    /**
     * Remove zone
     *
     * @param \Rg\ApiBundle\Entity\Zone $zone
     */
    public function removeZone(\Rg\ApiBundle\Entity\Zone $zone)
    {
        $this->zones->removeElement($zone);
    }

    /**
     * Get zones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getZones()
    {
        return $this->zones;
    }
}

