<?php

namespace Rg\ApiBundle\Entity;

/**
 * PostalIndex
 */
class PostalIndex
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var \Rg\ApiBundle\Entity\Product
     */
    private $product;

    /**
     * @var \Rg\ApiBundle\Entity\Timeblock
     */
    private $timeblock;


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
     * Set value
     *
     * @param string $value
     *
     * @return PostalIndex
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set product
     *
     * @param \Rg\ApiBundle\Entity\Product $product
     *
     * @return PostalIndex
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
     * Set timeblock
     *
     * @param \Rg\ApiBundle\Entity\Timeblock $timeblock
     *
     * @return PostalIndex
     */
    public function setTimeblock(\Rg\ApiBundle\Entity\Timeblock $timeblock = null)
    {
        $this->timeblock = $timeblock;

        return $this;
    }

    /**
     * Get timeblock
     *
     * @return \Rg\ApiBundle\Entity\Timeblock
     */
    public function getTimeblock()
    {
        return $this->timeblock;
    }
}
