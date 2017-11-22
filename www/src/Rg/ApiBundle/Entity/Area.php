<?php

namespace Rg\ApiBundle\Entity;

/**
 * Area
 */
class Area
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
     * @var integer
     */
    private $from_front_id;

    /**
     * @var string
     */
    private $works_id;

    /**
     * @var string
     */
    private $link;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $child_areas;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $cities;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $sales;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $promos;

    /**
     * @var \Rg\ApiBundle\Entity\Area
     */
    private $parent_area;

    /**
     * @var \Rg\ApiBundle\Entity\Zone
     */
    private $zone;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->child_areas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cities = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->promos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Area
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
     * Set fromFrontId
     *
     * @param integer $fromFrontId
     *
     * @return Area
     */
    public function setFromFrontId($fromFrontId)
    {
        $this->from_front_id = $fromFrontId;

        return $this;
    }

    /**
     * Get fromFrontId
     *
     * @return integer
     */
    public function getFromFrontId()
    {
        return $this->from_front_id;
    }

    /**
     * Set worksId
     *
     * @param string $worksId
     *
     * @return Area
     */
    public function setWorksId($worksId)
    {
        $this->works_id = $worksId;

        return $this;
    }

    /**
     * Get worksId
     *
     * @return string
     */
    public function getWorksId()
    {
        return $this->works_id;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Area
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Add childArea
     *
     * @param \Rg\ApiBundle\Entity\Area $childArea
     *
     * @return Area
     */
    public function addChildArea(\Rg\ApiBundle\Entity\Area $childArea)
    {
        $this->child_areas[] = $childArea;

        return $this;
    }

    /**
     * Remove childArea
     *
     * @param \Rg\ApiBundle\Entity\Area $childArea
     */
    public function removeChildArea(\Rg\ApiBundle\Entity\Area $childArea)
    {
        $this->child_areas->removeElement($childArea);
    }

    /**
     * Get childAreas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildAreas()
    {
        return $this->child_areas;
    }

    /**
     * Add city
     *
     * @param \Rg\ApiBundle\Entity\City $city
     *
     * @return Area
     */
    public function addCity(\Rg\ApiBundle\Entity\City $city)
    {
        $this->cities[] = $city;

        return $this;
    }

    /**
     * Remove city
     *
     * @param \Rg\ApiBundle\Entity\City $city
     */
    public function removeCity(\Rg\ApiBundle\Entity\City $city)
    {
        $this->cities->removeElement($city);
    }

    /**
     * Get cities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Add sale
     *
     * @param \Rg\ApiBundle\Entity\Sale $sale
     *
     * @return Area
     */
    public function addSale(\Rg\ApiBundle\Entity\Sale $sale)
    {
        $this->sales[] = $sale;

        return $this;
    }

    /**
     * Remove sale
     *
     * @param \Rg\ApiBundle\Entity\Sale $sale
     */
    public function removeSale(\Rg\ApiBundle\Entity\Sale $sale)
    {
        $this->sales->removeElement($sale);
    }

    /**
     * Get sales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * Add promo
     *
     * @param \Rg\ApiBundle\Entity\Promo $promo
     *
     * @return Area
     */
    public function addPromo(\Rg\ApiBundle\Entity\Promo $promo)
    {
        $this->promos[] = $promo;

        return $this;
    }

    /**
     * Remove promo
     *
     * @param \Rg\ApiBundle\Entity\Promo $promo
     */
    public function removePromo(\Rg\ApiBundle\Entity\Promo $promo)
    {
        $this->promos->removeElement($promo);
    }

    /**
     * Get promos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPromos()
    {
        return $this->promos;
    }

    /**
     * Set parentArea
     *
     * @param \Rg\ApiBundle\Entity\Area $parentArea
     *
     * @return Area
     */
    public function setParentArea(\Rg\ApiBundle\Entity\Area $parentArea = null)
    {
        $this->parent_area = $parentArea;

        return $this;
    }

    /**
     * Get parentArea
     *
     * @return \Rg\ApiBundle\Entity\Area
     */
    public function getParentArea()
    {
        return $this->parent_area;
    }

    /**
     * Set zone
     *
     * @param \Rg\ApiBundle\Entity\Zone $zone
     *
     * @return Area
     */
    public function setZone(\Rg\ApiBundle\Entity\Zone $zone = null)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return \Rg\ApiBundle\Entity\Zone
     */
    public function getZone()
    {
        return $this->zone;
    }
}
