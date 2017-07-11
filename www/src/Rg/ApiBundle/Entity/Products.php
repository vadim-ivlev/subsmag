<?php

namespace Rg\ApiBundle\Entity;

/**
 * Products
 */
class Products
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nameProduct;

    /**
     * @var string
     */
    private $keyword;

    /**
     * @var integer
     */
    private $frequency;

    /**
     * @var boolean
     */
    private $flagSubscribe;

    /**
     * @var boolean
     */
    private $flagBuy;

    /**
     * @var integer
     */
    private $postIndex;

    /**
     * @var string
     */
    private $image;

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
     * Set nameProduct
     *
     * @param string $nameProduct
     *
     * @return Products
     */
    public function setNameProduct($nameProduct)
    {
        $this->nameProduct = $nameProduct;

        return $this;
    }

    /**
     * Get nameProduct
     *
     * @return string
     */
    public function getNameProduct()
    {
        return $this->nameProduct;
    }

    /**
     * Set keyword
     *
     * @param string $keyword
     *
     * @return Products
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Get keyword
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set frequency
     *
     * @param integer $frequency
     *
     * @return Products
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * Get frequency
     *
     * @return integer
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * Set flagSubscribe
     *
     * @param boolean $flagSubscribe
     *
     * @return Products
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
     * Set flagBuy
     *
     * @param boolean $flagBuy
     *
     * @return Products
     */
    public function setFlagBuy($flagBuy)
    {
        $this->flagBuy = $flagBuy;

        return $this;
    }

    /**
     * Get flagBuy
     *
     * @return boolean
     */
    public function getFlagBuy()
    {
        return $this->flagBuy;
    }

    /**
     * Set postIndex
     *
     * @param integer $postIndex
     *
     * @return Products
     */
    public function setPostIndex($postIndex)
    {
        $this->postIndex = $postIndex;

        return $this;
    }

    /**
     * Get postIndex
     *
     * @return integer
     */
    public function getPostIndex()
    {
        return $this->postIndex;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Products
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
     * Set sort
     *
     * @param integer $sort
     *
     * @return Products
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
     * @return Products
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
    private $kits;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->kits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add kit
     *
     * @param \Rg\ApiBundle\Entity\Kits $kit
     *
     * @return Products
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
