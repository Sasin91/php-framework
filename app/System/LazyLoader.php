<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 15-04-15
 * Time: 18:43
 */

namespace System;


use System\Containers\ObjectContainer;

class LazyLoader
{

    /**
     * In future, this method is going to define whether or not this class has been instantiated.
     * @var bool
     */
    public static $booted = false;
    protected static $instance;
    public $container;

    /**
     * Init or Boot method, for a Laravel reference.
     */
    public static function _init()
    {
        /**
         * Register current instance
         */
        self::$instance = new static;
        $instance = self::$instance;

        /**
         * Create a new collection for LazyLoader.
         */
        $instance->container = new ObjectContainer();

        /**
         * Define LazyLoader is instantiated.
         */
        static::$booted = true;
    }

    /**
     * Register an object for easy access of that given object in it's given state.
     * @param $name
     * @param $object
     * @return bool
     */
    public static function add($name, $object)
    {
        if ($name !== '*' && $object !== '*') {
            if (is_object($object)) {
                static::$instance->container->set($name, $object);
            }
        }
        $call = static::get($name); // Workaround for arbitrary expression, which is only php 5.5+
        return !empty($call) ? true : false;
    }

    /**
     * Returns object or boolean
     * @param $name
     * @return object|bool
     */
    public static function get($name)
    {
            return static::inContainer($name);
    }

    private static function inContainer($name)
    {
        return static::$instance->container->get($name);
    }


    /**
     * Register what's LazyLoaded with the AutoLoader.
     */
    public static function register()
    {
        $instance = static::$instance;
        foreach ($instance->container->available() as $class) {
            \Autoloader::register($class);
        }
    }
}