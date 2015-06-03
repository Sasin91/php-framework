<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 19-05-15
 * Time: 13:30
 */

namespace System;

/**
 * Class Compositor
 * Allows merging two classes or objects together WITH methods, provided names are NOT equal.
 * @package System
 */
class Compositor
{

    /**
     * Array of Objects
     * @var array
     */
    private $objects = array();

    /**
     * This class assumes the array to be in a associative namespace => class manner.
     * @param array $objects
     */
    public function __construct(array $objects)
    {
        $this->objects = $objects;
    }

    /**
     * @param $fqns (Fully Qualified NameSpace)
     * @param $call
     * @param array $arguments
     * @return mixed
     */
    public function __get($fqns, $call, array $arguments = array())
    {
        foreach ($this->objects as $namespace => $object) {
            if ($namespace == $fqns)
                if (in_array($call, get_class_methods($object))) {
                    if (!empty($arguments))
                        return $object->$call($arguments);
                    return $object->$call();
                }
        }
    }
}