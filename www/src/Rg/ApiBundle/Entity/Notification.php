<?php

namespace Rg\ApiBundle\Entity;

/**
 * Notification
 */
class Notification
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $order_created;

    /**
     * @var string
     */
    private $order_paid;

    /**
     * @var \Rg\ApiBundle\Entity\Order
     */
    private $order;


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
     * Set orderCreated
     *
     * @param string $orderCreated
     *
     * @return Notification
     */
    public function setOrderCreated($orderCreated)
    {
        $this->order_created = $orderCreated;

        return $this;
    }

    /**
     * Get orderCreated
     *
     * @return string
     */
    public function getOrderCreated()
    {
        return $this->order_created;
    }

    /**
     * Set orderPaid
     *
     * @param string $orderPaid
     *
     * @return Notification
     */
    public function setOrderPaid($orderPaid)
    {
        $this->order_paid = $orderPaid;

        return $this;
    }

    /**
     * Get orderPaid
     *
     * @return string
     */
    public function getOrderPaid()
    {
        return $this->order_paid;
    }

    /**
     * Set order
     *
     * @param \Rg\ApiBundle\Entity\Order $order
     *
     * @return Notification
     */
    public function setOrder(\Rg\ApiBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Rg\ApiBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $state;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $error;


    /**
     * Set type
     *
     * @param string $type
     *
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Notification
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Notification
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
     * Set error
     *
     * @param string $error
     *
     * @return Notification
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Get error
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}
