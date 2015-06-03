<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 14-01-15
 * Time: 12:57
 */

namespace System\Containers;


use System\Interfaces\ContainerInterface;

abstract class Container implements ContainerInterface
{

    private $container = array();
    private $object;

    function available()
    {
        return $this->container;
    }

    function get($element)
    {
        if ($this->exist($element)) {
            $request = $this->container[$element];
            $this->object = $request;
        }
    }

    private function exist($element)
    {
        if (isset($this->container[$element])) {
            return true;
        }
        return false;
    }

    function raw()
    {
        return $this->object;
    }

    function asObject()
    {
        return json_encode(json_decode($this->object));
    }

    function set($element)
    {
        if (!$this->exist($element)) {
            $this->container[$element];
            return true;
        }
        return $element . 'already exists.';
    }

    function remove($element)
    {
        if ($this->exist($element)) {
            unset($this->container[$element]);
            return true;
        }
        return $element . ' does not exist.';
    }
}