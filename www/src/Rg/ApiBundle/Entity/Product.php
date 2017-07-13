<?php

namespace Rg\ApiBundle\Entity;

/**
 * Product
 */
class Product
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
    private $description;

    /**
     * @var string
     */
    private $postal_index;

    /**
     * @var boolean
     */
    private $is_active;

    /**
     * @var boolean
     */
    private $is_subscribable;

    /**
     * @var integer
     */
    private $sort;


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
     * @return Product
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
     * Set description
     *
     * @param string $description
     *
     * @return Product
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
     * Set postalIndex
     *
     * @param string $postalIndex
     *
     * @return Product
     */
    public function setPostalIndex($postalIndex)
    {
        $this->postal_index = $postalIndex;

        return $this;
    }

    /**
     * Get postalIndex
     *
     * @return string
     */
    public function getPostalIndex()
    {
        return $this->postal_index;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Product
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
     * Set isSubscribable
     *
     * @param boolean $isSubscribable
     *
     * @return Product
     */
    public function setIsSubscribable($isSubscribable)
    {
        $this->is_subscribable = $isSubscribable;

        return $this;
    }

    /**
     * Get isSubscribable
     *
     * @return boolean
     */
    public function getIsSubscribable()
    {
        return $this->is_subscribable;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     *
     * @return Product
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
}
