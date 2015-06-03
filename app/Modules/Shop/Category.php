<?php

namespace Modules\Shop;
/**
 * Class Category
 * @package Modules\Shop
 */
class Category
{

    public $label;
    public $description;
    public $picture;
    public $link;

    /**
     * @param $category
     * @param $description
     * @param $picture
     */
    public function __construct($category, $description, $link, $picture)
    {
        $this->label = $category;
        $this->description = $description;
        $this->link = $link;
        $this->picture = $picture;
    }
}