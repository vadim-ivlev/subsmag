<?php

namespace Rg\ApiBundle\Entity;

/**
 * Order
 */
class Order
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $email;

    /**
     * @var float
     */
    private $total;

    /**
     * @var boolean
     */
    private $is_promoted;

    /**
     * @var boolean
     */
    private $is_paid;

    /**
     * @var string
     */
    private $pg_payment_id;

    /**
     * @var string
     */
    private $platron_init_xml;

    /**
     * @var string
     */
    private $platron_receipt_create_xml;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var string
     */
    private $postcode;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $building_number;

    /**
     * @var string
     */
    private $building_subnumber;

    /**
     * @var string
     */
    private $building_part;

    /**
     * @var string
     */
    private $appartment;

    /**
     * @var \Rg\ApiBundle\Entity\Pin
     */
    private $pin;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $items;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $patritems;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $notifications;

    /**
     * @var \Rg\ApiBundle\Entity\Payment
     */
    private $payment;

    /**
     * @var \Rg\ApiBundle\Entity\User
     */
    private $user;

    /**
     * @var \Rg\ApiBundle\Entity\Legal
     */
    private $legal;

    /**
     * @var \Rg\ApiBundle\Entity\City
     */
    private $city;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->patritems = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Order
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
     * Set name
     *
     * @param string $name
     *
     * @return Order
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
     * Set phone
     *
     * @param string $phone
     *
     * @return Order
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Order
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
     * Set email
     *
     * @param string $email
     *
     * @return Order
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return Order
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set isPromoted
     *
     * @param boolean $isPromoted
     *
     * @return Order
     */
    public function setIsPromoted($isPromoted)
    {
        $this->is_promoted = $isPromoted;

        return $this;
    }

    /**
     * Get isPromoted
     *
     * @return boolean
     */
    public function getIsPromoted()
    {
        return $this->is_promoted;
    }

    /**
     * Set isPaid
     *
     * @param boolean $isPaid
     *
     * @return Order
     */
    public function setIsPaid($isPaid)
    {
        $this->is_paid = $isPaid;

        return $this;
    }

    /**
     * Get isPaid
     *
     * @return boolean
     */
    public function getIsPaid()
    {
        return $this->is_paid;
    }

    /**
     * Set pgPaymentId
     *
     * @param string $pgPaymentId
     *
     * @return Order
     */
    public function setPgPaymentId($pgPaymentId)
    {
        $this->pg_payment_id = $pgPaymentId;

        return $this;
    }

    /**
     * Get pgPaymentId
     *
     * @return string
     */
    public function getPgPaymentId()
    {
        return $this->pg_payment_id;
    }

    /**
     * Set platronInitXml
     *
     * @param string $platronInitXml
     *
     * @return Order
     */
    public function setPlatronInitXml($platronInitXml)
    {
        $this->platron_init_xml = $platronInitXml;

        return $this;
    }

    /**
     * Get platronInitXml
     *
     * @return string
     */
    public function getPlatronInitXml()
    {
        return $this->platron_init_xml;
    }

    /**
     * Set platronReceiptCreateXml
     *
     * @param string $platronReceiptCreateXml
     *
     * @return Order
     */
    public function setPlatronReceiptCreateXml($platronReceiptCreateXml)
    {
        $this->platron_receipt_create_xml = $platronReceiptCreateXml;

        return $this;
    }

    /**
     * Get platronReceiptCreateXml
     *
     * @return string
     */
    public function getPlatronReceiptCreateXml()
    {
        return $this->platron_receipt_create_xml;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Order
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     *
     * @return Order
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Order
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set buildingNumber
     *
     * @param string $buildingNumber
     *
     * @return Order
     */
    public function setBuildingNumber($buildingNumber)
    {
        $this->building_number = $buildingNumber;

        return $this;
    }

    /**
     * Get buildingNumber
     *
     * @return string
     */
    public function getBuildingNumber()
    {
        return $this->building_number;
    }

    /**
     * Set buildingSubnumber
     *
     * @param string $buildingSubnumber
     *
     * @return Order
     */
    public function setBuildingSubnumber($buildingSubnumber)
    {
        $this->building_subnumber = $buildingSubnumber;

        return $this;
    }

    /**
     * Get buildingSubnumber
     *
     * @return string
     */
    public function getBuildingSubnumber()
    {
        return $this->building_subnumber;
    }

    /**
     * Set buildingPart
     *
     * @param string $buildingPart
     *
     * @return Order
     */
    public function setBuildingPart($buildingPart)
    {
        $this->building_part = $buildingPart;

        return $this;
    }

    /**
     * Get buildingPart
     *
     * @return string
     */
    public function getBuildingPart()
    {
        return $this->building_part;
    }

    /**
     * Set appartment
     *
     * @param string $appartment
     *
     * @return Order
     */
    public function setAppartment($appartment)
    {
        $this->appartment = $appartment;

        return $this;
    }

    /**
     * Get appartment
     *
     * @return string
     */
    public function getAppartment()
    {
        return $this->appartment;
    }

    /**
     * Set pin
     *
     * @param \Rg\ApiBundle\Entity\Pin $pin
     *
     * @return Order
     */
    public function setPin(\Rg\ApiBundle\Entity\Pin $pin = null)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Get pin
     *
     * @return \Rg\ApiBundle\Entity\Pin
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * Add item
     *
     * @param \Rg\ApiBundle\Entity\Item $item
     *
     * @return Order
     */
    public function addItem(\Rg\ApiBundle\Entity\Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \Rg\ApiBundle\Entity\Item $item
     */
    public function removeItem(\Rg\ApiBundle\Entity\Item $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add patritem
     *
     * @param \Rg\ApiBundle\Entity\Patritem $patritem
     *
     * @return Order
     */
    public function addPatritem(\Rg\ApiBundle\Entity\Patritem $patritem)
    {
        $this->patritems[] = $patritem;

        return $this;
    }

    /**
     * Remove patritem
     *
     * @param \Rg\ApiBundle\Entity\Patritem $patritem
     */
    public function removePatritem(\Rg\ApiBundle\Entity\Patritem $patritem)
    {
        $this->patritems->removeElement($patritem);
    }

    /**
     * Get patritems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatritems()
    {
        return $this->patritems;
    }

    /**
     * Add notification
     *
     * @param \Rg\ApiBundle\Entity\Notification $notification
     *
     * @return Order
     */
    public function addNotification(\Rg\ApiBundle\Entity\Notification $notification)
    {
        $this->notifications[] = $notification;

        return $this;
    }

    /**
     * Remove notification
     *
     * @param \Rg\ApiBundle\Entity\Notification $notification
     */
    public function removeNotification(\Rg\ApiBundle\Entity\Notification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Set payment
     *
     * @param \Rg\ApiBundle\Entity\Payment $payment
     *
     * @return Order
     */
    public function setPayment(\Rg\ApiBundle\Entity\Payment $payment = null)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return \Rg\ApiBundle\Entity\Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set user
     *
     * @param \Rg\ApiBundle\Entity\User $user
     *
     * @return Order
     */
    public function setUser(\Rg\ApiBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Rg\ApiBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set legal
     *
     * @param \Rg\ApiBundle\Entity\Legal $legal
     *
     * @return Order
     */
    public function setLegal(\Rg\ApiBundle\Entity\Legal $legal = null)
    {
        $this->legal = $legal;

        return $this;
    }

    /**
     * Get legal
     *
     * @return \Rg\ApiBundle\Entity\Legal
     */
    public function getLegal()
    {
        return $this->legal;
    }

    /**
     * Set city
     *
     * @param \Rg\ApiBundle\Entity\City $city
     *
     * @return Order
     */
    public function setCity(\Rg\ApiBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \Rg\ApiBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }
}

