<?php

namespace Rg\ApiBundle\Entity;

/**
 * Kits
 */
class Kits
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nameKit;

    /**
     * @var boolean
     */
    private $flagSubscribe;

    /**
     * @var integer
     */
    private $sort;

    /**
     * @var string
     */
    private $description;


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
     * @return boolean
     */
    public function getFlagSubscribe()
    {
        return $this->flagSubscribe;
    }

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
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $products;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add product
     *
     * @param \Rg\ApiBundle\Entity\Products $product
     *
     * @return Kits
     */
    public function addProduct(\Rg\ApiBundle\Entity\Products $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \Rg\ApiBundle\Entity\Products $product
     */
    public function removeProduct(\Rg\ApiBundle\Entity\Products $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
}
