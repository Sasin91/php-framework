<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 05-05-15
 * Time: 13:23
 */

namespace Http\Models\Blog;


use Http\Models\Blog;

class Category extends Blog {

    public $categories = array();
    private $keys = array();

    public function newCategory($label, $description)
    {
        $this->categories[$label] = compact('label', 'description');
        $this->keys = array_keys($this->categories[$label]);
    }

    public function get($label)
    {
        return $this->categories[$label];
    }

    public function available()
    {
        return $this->categories;
    }

    public function change($label, array $content = array())
    {
        foreach ($content as $key => $value) {
            if(in_array($key, $this->keys))
            {
                $this->categories[$label] = array(
                    $key => $value
                );
            }
        }

    }

}