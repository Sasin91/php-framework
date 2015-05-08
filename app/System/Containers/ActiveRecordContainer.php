<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 14-01-15
 * Time: 12:54
 */

namespace System\Containers;

class ActiveRecordContainer extends ObjectContainer
{
    private $collection = array();
    public $keys;

    public function keys($array = array())
    {
        $this->verify($array);
        return array_keys($array);
    }

    public function length($array = array())
    {
        $this->verify($array);
        return count($this->collection, COUNT_RECURSIVE);
    }

    public function isMultidimensional($array = array())
    {
        $this->verify($array);
        $this->getMultidimensionalIndexes();
        return !empty($this->keys) ? true : false;
    }

    /**
     * returns keys if array is multidimensional
     * @var
     */
    private function getMultidimensionalIndexes()
    {
        array_walk_recursive($this->collection, function($value, $key) { if(is_array($value)) $this->keys[] = $value; });
    }

    /**
     * verifies integrity and existence of $array
     * if empty assume container is our array.
     * passes to clean.
     * @param array $array
     */
    private function verify($array = array())
    {
        if(empty($array))
            $this->collection[] = $this->container;
        array_filter($array, "clean");
    }

    /**
     * strips php & html tags, trims white spaces, sanitize input.
     * puts $collection.
     * @param $string
     * @return mixed
     */
    private function clean($string)
    {
        $this->collection[] = filter_var(strip_tags(trim($string), FILTER_SANITIZE_STRING));
    }
}