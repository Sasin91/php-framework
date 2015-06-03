<?php

namespace System;

/**
 * PHP Reflection on steroids.
 * Class Reflector
 * @package System
 */
class Reflector implements \Reflector
{
    /**
     * Instance of PHP Reflection
     * @var
     */
    private $reflection;

    /**
     * Method Reflect, tries if LazyLoader (which if isn't aware of request, asks AutoLoader and Composer),
     * If none is aware of an instance of given class, verifies class isn't Abstract then creates a Reflection and registers it with LazyLoader.
     * @param null $classOrMethod
     * @param null $params
     * @return bool|object
     */
    public static function reflect($classOrMethod = null, $params = null)
    {
        $instance = new static;
        $isLoaded = LazyLoader::get($classOrMethod);
        if ($isLoaded != false)
            return $isLoaded;

        $instance->reflection = new \ReflectionClass($classOrMethod);
        if ($instance->reflection->isAbstract())
            return false;

        if (!$params == NULL):
            if (is_object($params)) {
                $param[] = $params;
            } else {
                $param = $params;
            }
            LazyLoader::add($classOrMethod, $instance->reflection->newInstanceArgs($param));
        endif;
        LazyLoader::add($classOrMethod, $instance->reflection->newInstanceWithoutConstructor());
        return LazyLoader::get($classOrMethod);
    }

    /**
     * Required by \Reflector, i could not find a clear documentation about this method.
     */
    public static function export()
    {
    }

    /**
     * Magic method __toString,
     * @documentation: http://php.net/manual/en/language.oop5.magic.php#object.tostring
     * @return mixed
     */
    public function __toString()
    {
        return $this->reflection;
    }
}