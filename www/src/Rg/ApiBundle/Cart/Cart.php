<?php
/**
 * Cart implementation
 */

namespace Rg\ApiBundle\Cart;


class Cart implements \JsonSerializable
{
    private $items = [];
    private $patritems = [];

    public function empty()
    {
        $this->items = [];
        $this->patritems = [];

        return true;
    }

    public function addItem(CartItem $cart_item)
    {
        $this->items[] = $cart_item;

        return true;
    }

    public function addPatritem(CartPatritem $cart_patritem)
    {
        $this->patritems[] = $cart_patritem;

        return true;
    }

    public function updateMultipleItemsByKeys(\stdClass $quantities)
    {
        foreach ($quantities as $key => $quantity) {
            if (!isset($this->items[(int) $key])) return false;

            /** @var CartItem $item */
            $item = $this->items[(int) $key];
            $item->setQuantity($quantity);
        }

        return true;
    }

    public function updateMultiplePatritemsByKeys(\stdClass $quantities)
    {
        foreach ($quantities as $key => $quantity) {
            if (!isset($this->patritems[(int) $key])) return false;

            /** @var CartPatritem $patritem */
            $patritem = $this->patritems[(int) $key];
            $patritem->setQuantity($quantity);
        }

        return true;
    }

    public function removeMultipleItemsByKeys(array $keys)
    {
        foreach ($keys as $key) {
            unset($this->items[$key]);
        }

        $this->items = array_values($this->items);

        return true;
    }

    public function removeMultiplePatritemsByKeys(array $keys)
    {
        foreach ($keys as $key) {
            unset($this->patritems[$key]);
        }

        $this->patritems = array_values($this->patritems);

        return true;
    }

    public function removeItemByKey(int $key)
    {
        unset($this->items[$key]);

        $this->items = array_values($this->items);

        return true;
    }

    public function removePatritemByKey(int $key)
    {
        unset($this->patritems[$key]);

        $this->patritems = array_values($this->patritems);

        return true;
    }

    public function getCartItems()
    {
        return $this->items;
    }

    public function getCartPatritems()
    {
        return $this->patritems;
    }

    function jsonSerialize()
    {
        return [
            'products' => $this->items,
            'archives' => $this->patritems,
        ];
    }
}