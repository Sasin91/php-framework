<?php

namespace Core\Application\Cart;
/**
 * Class Item
 * @package Core\Application\Cart
 */
class Item {

    public $category;
    public $id;
    public $label;
    public $qty;
    public $price;
    public $pictures = array();


    /**
     * Create new item.
     * @param $category
     * @param $id
     * @param $label
     * @param $qty
     * @param $price
     * @param array $pictures
     */
    public function __construct($category, $id, $label, $qty, $price, array $pictures = array())
    {
        $this->category = $category;
        $this->id = $id;
        $this->label = $label;
        $this->qty = $qty;
        $this->price = $price;
        $this->pictures[] = $pictures;
    }
}