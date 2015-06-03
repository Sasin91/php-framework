<?php

namespace System\Traits;

trait ArrayAccess
{

    public $container = array();

    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->container[$offset];
        }
        return false;
    }

    public function offsetExists($offset)
    {
        if (is_string($offset))
            return array_key_exists($offset, $this->container);
    }

    public function offsetSet($offset, $value)
    {
        if (!$this->offsetExists($offset)) {
            if (is_string($offset) && !empty($value))
                $this->container[$offset] = $value;
            return true;
        }
        return $offset . ' Already exists.';
    }

    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->container[$offset]);
            return true;
        }
        return $offset . ' does not exist.';
    }

}