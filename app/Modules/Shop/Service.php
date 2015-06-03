<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 30-04-15
 * Time: 21:40
 */

namespace Modules\Shop;


class Service
{

    public $service;

    public $price;

    public $description;


    public function __construct($service, $price, $description)
    {
        $this->service = $service;
        $this->price = $price;
        $this->description = $description;
    }
}