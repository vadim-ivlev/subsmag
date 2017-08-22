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
    private $quantity;
    private $patriff;

    public function __construct(
        int $quantity,
        int $patriff
    )
    {
        $this->quantity = $quantity;
        $this->patriff = $patriff;
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

    /**
     * @return int
     */
    public function getPatriff(): int
    {
        return $this->patriff;
    }

    /**
     * @param int $patriff
     */
    public function setPatriff(int $patriff)
    {
        $this->patriff = $patriff;
    }

    function jsonSerialize()
    {
        return [
            "quantity" => $this->getQuantity(),
            "patriff" => $this->getPatriff(),
        ];
    }
}