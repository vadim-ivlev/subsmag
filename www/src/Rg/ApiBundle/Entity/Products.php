<?php

namespace Rg\ApiBundle\Entity;

/**
 * Products
 */
class Products
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nameProduct;

    /**
     * @var int
     */
    private $frequency;

    /**
     * @var bool
     */
    private $flagSubscribe;

    /**
     * @var bool
     */
    private $flagBuy;

    /**
     * @var int
     */
    private $postIndex;

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
     * @return int
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
     * @return bool
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
     * @return bool
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
     * @return int
     */
    public function getPostIndex()
    {
        return $this->postIndex;
    }
    /**
     * @var string
     */
    private $keyword;


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
     * @var integer
     */
    private $sort;


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
     * @var string
     */
    private $description;


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
}
