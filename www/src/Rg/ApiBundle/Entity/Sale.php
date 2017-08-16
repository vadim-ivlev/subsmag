<?php

namespace Rg\ApiBundle\Entity;

/**
 * Sale
 */
class Sale
{
    /**
     * @var integer
     */
    private $id;

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
    private $is_regional;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $items;

    /**
     * @var \Rg\ApiBundle\Entity\Product
     */
    private $product;

    /**
     * @var \Rg\ApiBundle\Entity\Month
     */
    private $month;

    /**
     * @var \Rg\ApiBundle\Entity\Area
     */
    private $area;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set start
     *
     * @param \DateTime $start
     *
     * @return Sale
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
     * @return Sale
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
     * Set isRegional
     *
     * @param boolean $isRegional
     *
     * @return Sale
     */
    public function setIsRegional($isRegional)
    {
        $this->is_regional = $isRegional;

        return $this;
    }

    /**
     * Get isRegional
     *
     * @return boolean
     */
    public function getIsRegional()
    {
        return $this->is_regional;
    }

    /**
     * Add item
     *
     * @param \Rg\ApiBundle\Entity\Item $item
     *
     * @return Sale
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
     * Set product
     *
     * @param \Rg\ApiBundle\Entity\Product $product
     *
     * @return Sale
     */
    public function setProduct(\Rg\ApiBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Rg\ApiBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set month
     *
     * @param \Rg\ApiBundle\Entity\Month $month
     *
     * @return Sale
     */
    public function setMonth(\Rg\ApiBundle\Entity\Month $month = null)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return \Rg\ApiBundle\Entity\Month
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set area
     *
     * @param \Rg\ApiBundle\Entity\Area $area
     *
     * @return Sale
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
}
