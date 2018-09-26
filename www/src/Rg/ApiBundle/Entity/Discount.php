<?php

namespace Rg\ApiBundle\Entity;

/**
 * Discount
 */
class Discount
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $discount;

    /**
     * @var \Rg\ApiBundle\Entity\Product
     */
    private $product;

    /**
     * @var \Rg\ApiBundle\Entity\Promo
     */
    private $promo;


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
     * Set discount
     *
     * @param float $discount
     *
     * @return Discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set product
     *
     * @param \Rg\ApiBundle\Entity\Product $product
     *
     * @return Discount
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
     * Set promo
     *
     * @param \Rg\ApiBundle\Entity\Promo $promo
     *
     * @return Discount
     */
    public function setPromo(\Rg\ApiBundle\Entity\Promo $promo = null)
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get promo
     *
     * @return \Rg\ApiBundle\Entity\Promo
     */
    public function getPromo()
    {
        return $this->promo;
    }
}

