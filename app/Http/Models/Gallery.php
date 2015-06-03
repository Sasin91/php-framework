<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 25-05-2015
 * Time: 00:12
 */

namespace Http\Models;


use System\Reflector;

class Gallery extends Page {

    protected $table = 'gallery';

    protected $belongsToOne = 'Http\Models\Page';


    public function get($gallery)
    {
        $images = Reflector::reflect('Http\Models\Images');
        return $images->getImages($gallery);
    }
}