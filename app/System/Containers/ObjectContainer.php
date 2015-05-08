<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 14-01-15
 * Time: 13:10
 */

namespace System\Containers;

use System\Traits\ArrayAccess;

class ObjectContainer implements \ArrayAccess {

    use ArrayAccess;

    protected static $_instances = array();

    public static function create($container)
    {
        $instance = new static;
        self::$_instances[] = $instance;
        $instance->set($container, array());
        return $instance;
    }


    public function destroyInstance()
    {
        unset(self::$_instances[array_search($this, self::$_instances, true)]);
    }

    /**
     * @param $includeSubclasses Optionally include subclasses in returned set
     * @returns array array of objects
     */
    public static function getInstances($includeSubclasses = false)
    {
        $me = get_class(new static);
        $return = array();
        foreach(self::$_instances as $instance) {
            if ($instance instanceof $me) {
                if ($includeSubclasses || (get_class($instance) === $me)) {
                    $return[] = $instance;
                }
            }
        }
        return $return;
    }

    /**
     * @return array
     */
    public function available()
    {
        return $this->container;
    }

    /**
     * Sets an object in the container array with name as key.
     * @param $name
     * @param $object
     * @return bool|string
     */
    public function set($name, $object)
    {
        if($this->exists($name))
        {
            $content = $this->get($name);
            $this->remove($name);
            if(is_array($object))
            {
                return $this->offsetSet($name, $content + $object);
            } else {
                $array = array(array_pop(explode('\\', $object)) => $object);
                return $this->offsetSet($name, $content + $array);
            }
        } else {
            return $this->offsetSet($name, $object);
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function exists($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * @param $name
     * @return bool|string
     */
    public function remove($name)
    {
        return $this->offsetUnset($name);
    }

    public static function __callStatic($method, $args)
    {
        $instance = new static;
        if(method_exists(__CLASS__, $method)){
            if($args)
                return $instance->$method($args);
            return$instance->$method();
        }
    }
}