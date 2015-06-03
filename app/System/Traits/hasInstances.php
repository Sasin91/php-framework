<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 14-05-15
 * Time: 01:47
 */

namespace System\Traits;


trait hasInstances
{

    public static $_instances = array();

    public static function _getInstance($class = '')
    {
        $instance = new static;
        if (empty($class)) {
            $class = $instance->calling_class();
        }
        return isset(self::$_instances[$class]) ? self::$_instances[$class] : new static;
    }

    /**
     * @param $includeSubclasses Optionally include subclasses in returned set
     * @returns array array of objects
     */
    public static function getAllInstanceOf($class, $includeSubclasses = false)
    {
        if (empty($class)) {
            $class = get_class(new static);
        }
        $return = array();
        foreach (self::$_instances as $instance) {
            if ($instance instanceof $class) {
                if ($includeSubclasses || (get_class($instance) === $class)) {
                    $return[] = $instance;
                }
            }
        }
        return $return;
    }

    /**
     * Update an existing instance of a class
     * @param $class
     * @param $new
     */
    public static function updateInstance($new, $class = '')
    {
        $previous = static::_getInstance($class);
        if(is_object($previous)) {
            $instance = new static;
            if (empty($class)) {
                $class = $instance->calling_class();
            }
            $instance->_destroyInstance($class);
            $instance->_saveInstance($new);
        }
    }

    public function getAllInstances()
    {
        return self::$_instances;
    }

    public function _saveInstance($instance, $class = '')
    {
        if(empty($class))
        {
            $class = $this->calling_class();
        }
        if ($instance instanceof $this) {
            self::$_instances[$class] = $instance;
        }
        return true;
    }

    public function _destroyInstance($class = '')
    {
        if(empty($class))
        {
            $class = $this->calling_class();
        }
        unset(self::$_instances[array_search($class, self::$_instances, true)]);
    }
}