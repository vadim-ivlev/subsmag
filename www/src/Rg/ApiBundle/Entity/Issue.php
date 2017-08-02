<?php

namespace Rg\ApiBundle\Entity;

/**
 * Issue
 */
class Issue
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $month;

    /**
     * @var integer
     */
    private $year;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $image;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $summaries;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $patriffs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->summaries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->patriffs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set month
     *
     * @param integer $month
     *
     * @return Issue
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Issue
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Issue
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
     * Set text
     *
     * @param string $text
     *
     * @return Issue
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Issue
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
     * Add summary
     *
     * @param \Rg\ApiBundle\Entity\Summary $summary
     *
     * @return Issue
     */
    public function addSummary(\Rg\ApiBundle\Entity\Summary $summary)
    {
        $this->summaries[] = $summary;

        return $this;
    }

    /**
     * Remove summary
     *
     * @param \Rg\ApiBundle\Entity\Summary $summary
     */
    public function removeSummary(\Rg\ApiBundle\Entity\Summary $summary)
    {
        $this->summaries->removeElement($summary);
    }

    /**
     * Get summaries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSummaries()
    {
        return $this->summaries;
    }

    /**
     * Add patriff
     *
     * @param \Rg\ApiBundle\Entity\Patriff $patriff
     *
     * @return Issue
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
}

