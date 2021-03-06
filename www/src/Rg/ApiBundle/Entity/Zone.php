<?php

namespace Rg\ApiBundle\Entity;

/**
 * Zone
 */
class Zone
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tariffs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $areas;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $patriffs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $promos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tariffs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->areas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->patriffs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Zone
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
     * @return Zone
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
     * Add tariff
     *
     * @param \Rg\ApiBundle\Entity\Tariff $tariff
     *
     * @return Zone
     */
    public function addTariff(\Rg\ApiBundle\Entity\Tariff $tariff)
    {
        $this->tariffs[] = $tariff;

        return $this;
    }

    /**
     * Remove tariff
     *
     * @param \Rg\ApiBundle\Entity\Tariff $tariff
     */
    public function removeTariff(\Rg\ApiBundle\Entity\Tariff $tariff)
    {
        $this->tariffs->removeElement($tariff);
    }

    /**
     * Get tariffs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTariffs()
    {
        return $this->tariffs;
    }

    /**
     * Add area
     *
     * @param \Rg\ApiBundle\Entity\Area $area
     *
     * @return Zone
     */
    public function addArea(\Rg\ApiBundle\Entity\Area $area)
    {
        $this->areas[] = $area;

        return $this;
    }

    /**
     * Remove area
     *
     * @param \Rg\ApiBundle\Entity\Area $area
     */
    public function removeArea(\Rg\ApiBundle\Entity\Area $area)
    {
        $this->areas->removeElement($area);
    }

    /**
     * Get areas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAreas()
    {
        return $this->areas;
    }

    /**
     * Add patriff
     *
     * @param \Rg\ApiBundle\Entity\Patriff $patriff
     *
     * @return Zone
     */
    public function addPatriff(\Rg\ApiBundle\Entity\Patriff $patriff)
    {
        $this->patriffs[] = $patriff;

        return $this;
    }

    /**
     * Remove patriff
     *
     * @param \Rg\ApiBundle\Entity\Patriff $patriff
     */
    public function removePatriff(\Rg\ApiBundle\Entity\Patriff $patriff)
    {
        $this->patriffs->removeElement($patriff);
    }

    /**
     * Get patriffs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatriffs()
    {
        return $this->patriffs;
    }

    /**
     * Add promo
     *
     * @param \Rg\ApiBundle\Entity\Promo $promo
     *
     * @return Zone
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
}

