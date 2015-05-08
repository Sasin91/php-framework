<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 15-04-15
 * Time: 18:43
 */

namespace System;


use System\Containers\ObjectContainer;

class LazyLoader {

    protected static $container = array();

    public static function _init()
    {
        $container = new ObjectContainer();
        static::$container = $container->create('LazyLoader');
    }

    public static function available()
    {
        return static::$container;
    }

    public static function get($name)
    {
        return !empty(static::$container[$name]) ? static::$container[$name] : false;
    }

    public static function set($name, $object)
    {
        if($name !== '*' && $object !== '*')
        {
            static::$container[$name] = $object;
        }
        return !empty(static::$container[$name]) ? true : false;
    }

    public static function register()
    {
        $instance = new static;
        foreach ($instance->available() as $class) {
            \Autoloader::register($class);
        }
    }

    public static function __callStatic($name, $arguments)
    {
        return static::$container->$name($arguments);
    }
}