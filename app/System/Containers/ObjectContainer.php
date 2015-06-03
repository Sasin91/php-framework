<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 14-01-15
 * Time: 13:10
 */

namespace System\Containers;

use System\Compositor;
use System\Traits\ArrayAccess;
use System\Traits\canBacktraceParent;
use System\Traits\hasInstances;

class ObjectContainer implements \ArrayAccess
{

    use ArrayAccess, hasInstances, canBacktraceParent;
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
        if (!$this->exists($name)) {
            return $this->offsetSet($name, $object);
        }
    }

    public function update($name, $object)
    {
        if ($this->exists($name)) {
            $this->remove($name);
        }
        return $this->set($name, $object);
    }

    public function merge($nameOfOriginal, $newObject)
    {
        $original = $this->get($nameOfOriginal);

        $this->remove($nameOfOriginal);

        if ($this->objectHasNoMethods($newObject) && $this->objectHasNoMethods($original))
            return $this->set($nameOfOriginal, (object)array_merge((array)$original, (array)$newObject));

        return new Compositor(compact('original', 'object'));
    }

    /**
     * @param $name
     * @return bool
     */
    public function get($name)
    {
        if($this->exists($name))
            return $this->offsetGet($name);
        return false;
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
        if($this->exists($name))
            return $this->offsetUnset($name);
        return false;
    }

    public static function __callStatic($method, $args)
    {
        $instance = new static;
        if (method_exists(__CLASS__, $method)) {
            if ($args)
                return $instance->$method($args);
            return $instance->$method();
        }
    }

    protected function objectHasNoMethods($object)
    {
        $methods = get_class_methods($object);
        return empty($methods) ? true : false;
    }
}