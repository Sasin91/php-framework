<?php

namespace System;


class Reflector implements \Reflector
{
    private $reflection = '';

  	public static function reflect($classOrMethod=null, $params=null)
  	{
        $instance = new static;
        $isLoaded = LazyLoader::get($classOrMethod);
        if($isLoaded)
            return $isLoaded;

        $instance->reflection = new \ReflectionClass($classOrMethod);
        if($instance->reflection->isAbstract())
            return false;

    if (!$params == NULL):
		if(is_object($params))
		{
			$param[] = $params;
		}
		else {
			$param = $params;
		}
            LazyLoader::set($classOrMethod, $instance->reflection->newInstanceArgs($param));
    endif;
        LazyLoader::set($classOrMethod, $instance->reflection->newInstanceWithoutConstructor());
        return LazyLoader::get($classOrMethod);
  	}

    public static function export(){}


  	public function __toString()
  	{
  		return $this->reflection;
  	}
}