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
    private $address;

    /**
     * @var float
     */
    private $total;

    /**
     * @var boolean
     */
    private $is_paid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $items;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $patritems;

    /**
     * @var \Rg\ApiBundle\Entity\Payment
     */
    private $payment;

    /**
     * @var \Rg\ApiBundle\Entity\User
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->patritems = new \Doctrine\Common\Collections\ArrayCollection();
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
    private $email;


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
     * @var string
     */
    private $pg_payment_id;


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
}
