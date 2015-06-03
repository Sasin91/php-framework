<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 14-05-2015
 * Time: 22:31
 */

namespace System\Containers;


class Collection
{

    /**
     * The collections Attributes, what's inside of it.
     * @var array
     */
    public $attributes = array();

    /**
     * Additional Variables,
     * either defined as $collection->varX = '';
     * or directly in this array.
     * @var array
     */
    public $additionalVariables = array();

    /**
     * The name of the collection
     * @var
     */
    public $name;

    /**
     * @param $name
     * @param array $attributes
     */
    public function __construct($name, array $attributes)
    {
        $this->name = $name;

        $this->attributes = $attributes;

        return $this;
    }

    /**
     * return a attribute
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        if ($this->exist($key)) {
            return $this->attributes[$key];
        }
        return false;
    }

    /**
     * @param $key
     * @return bool
     */
    public function exist($key)
    {
        return in_array($key, $this->attributes);
    }

    /**
     * add a attribute
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        if (!$this->exist($key)) {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * Save the additional variables, if any.
     */
    public function save()
    {
        if (empty($this->additionalVariables)) {
            $this->hasAdditionalVariables();
        }
    }

    /**
     * Checks if the instance of Collection has additional variables
     */
    protected function hasAdditionalVariables()
    {
        $this->getVariables();
        foreach ($this->additionalVariables as $key => $variable) {
            $this->add($key, $variable);
        }
    }

    /**
     * Get the variables
     */
    private function getVariables()
    {
        $this->additionalVariables = get_class_vars(__CLASS__);
    }
}