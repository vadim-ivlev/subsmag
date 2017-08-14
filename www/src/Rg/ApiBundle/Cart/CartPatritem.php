<?php
/**
    {
        "id": 6,
        "delivery": 1,
        "year": 2017,
        "issue": 3,
        "quantity": 1
    },
 */

namespace Rg\ApiBundle\Cart;


class CartPatritem implements \JsonSerializable
{
    private $id;
    private $delivery;
    private $year;
    private $issue;
    private $quantity;

    public function __construct(
        int $id,
        int $delivery,
        int $year,
        int $issue,
        int $quantity
    )
    {
        $this->id = $id;
        $this->delivery = $delivery;
        $this->year = $year;
        $this->issue = $issue;
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * @param int $delivery
     */
    public function setDelivery(int $delivery)
    {
        $this->delivery = $delivery;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear(int $year)
    {
        $this->year = $year;
    }

    /**
     * @return int
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * @param int $issue
     */
    public function setIssue(int $issue)
    {
        $this->issue = $issue;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "delivery" => $this->getDelivery(),
            "year" => $this->getYear(),
            "issue" => $this->getIssue(),
            "quantity" => $this->getQuantity(),
        ];
    }
}