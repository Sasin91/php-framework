<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 20-05-15
 * Time: 13:31
 */

namespace System;


class Dispatch {

    protected $classes = array();

    /**
     * @param array $classes
     */
    public function __construct(array $classes)
    {
       $this->classes = $classes;
    }

    /**
     * Dispatch a message to a class
     * @param array $classes
     * @param $receiver
     * @param string $method
     * @param $message
     * @return bool
     */
    public function fire($receiver, $sender, $message, $method)
    {
        if(in_array($receiver, array_keys($this->classes)))
        {
            $class = $this->classes[$receiver]; // Should be a ReflectionClass.
            $class->parseMessage($method, $sender, $message); // defined in System\MVC\Core()
            return true;
        }
        return false;
    }
}