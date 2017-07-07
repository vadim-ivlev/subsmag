<?php

namespace Rg\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Orders
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned": true, "comment" : "ID заказа"})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $userId;

    /**
     * @var int
     * @ORM\Column(name="product_id", type="integer", length=11, options={"default":""})
     */
    private $productId;

    /**
     * @var int
     * @ORM\Column(name="kit_id", type="integer", length=11, options={"default":""})
     */
    private $kitId;

    /**
     * @var int
     * @ORM\Column(name="zone_id", type="integer", length=11, options={"default":""})
     */
    private $zoneId;

    /**
     * @var int
     * @ORM\Column(name="subscribe_id", type="integer", length=11, options={"default":""})
     */
    private $subscribeId;

    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     * @ORM\Column(name="address", type="string", length=128, options={"default":""})
     */
    private $address;

    /**
     * @var float
     * @ORM\Column(name="price", type="float", length=128, options={"default":""})
     */
    private $price;

    /**
     * @var bool
     * @ORM\Column(name="flag_paid", type="boolean")
     */
    private $flagPaid;


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
     * Set userId
     *
     * @param integer $userId
     *
     * @return Orders
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     *
     * @return Orders
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set kitId
     *
     * @param integer $kitId
     *
     * @return Orders
     */
    public function setKitId($kitId)
    {
        $this->kitId = $kitId;

        return $this;
    }

    /**
     * Get kitId
     *
     * @return int
     */
    public function getKitId()
    {
        return $this->kitId;
    }

    /**
     * Set zoneId
     *
     * @param integer $zoneId
     *
     * @return Orders
     */
    public function setZoneId($zoneId)
    {
        $this->zoneId = $zoneId;

        return $this;
    }

    /**
     * Get zoneId
     *
     * @return int
     */
    public function getZoneId()
    {
        return $this->zoneId;
    }

    /**
     * Set subscribeId
     *
     * @param integer $subscribeId
     *
     * @return Orders
     */
    public function setSubscribeId($subscribeId)
    {
        $this->subscribeId = $subscribeId;

        return $this;
    }

    /**
     * Get subscribeId
     *
     * @return int
     */
    public function getSubscribeId()
    {
        return $this->subscribeId;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Orders
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Orders
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Orders
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set flagPaid
     *
     * @param boolean $flagPaid
     *
     * @return Orders
     */
    public function setFlagPaid($flagPaid)
    {
        $this->flagPaid = $flagPaid;

        return $this;
    }

    /**
     * Get flagPaid
     *
     * @return bool
     */
    public function getFlagPaid()
    {
        return $this->flagPaid;
    }
}
