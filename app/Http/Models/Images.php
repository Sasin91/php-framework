<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 25-05-2015
 * Time: 00:13
 */

namespace Http\Models;


class Images extends Gallery {

    protected $table = 'images';

    protected $belongsToOne = 'Http\Models\Gallery';

    public function getImages($gallery)
    {
        return $this->select('*', $this->table)->where('gallery', '=', $gallery)->execute();
    }
}