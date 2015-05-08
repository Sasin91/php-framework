<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 25-01-15
 * Time: 02:37
 */

namespace Modules;


use System\Exception\BadMethodCallException;

abstract class ArrayObject extends \ArrayObject {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * By extending this class, a class becomes able to become accessed as an Array, Array_* functions become available through $this->Array_*
     * @param $func
     * @param $argv
     * @return mixed
     * @throws BadMethodCallException
     */
    public function __call($func, $argv)
    {
        if (!is_callable($func) || substr($func, 0, 6) !== 'array_')
        {
            throw new BadMethodCallException(__CLASS__.'->'.$func);
        }
        return call_user_func_array($func, array_merge(array($this->getArrayCopy()), $argv));
    }

    public function __sleep()
    {
        echo 'A class extending this is able to call Array_* functions by appending $this-> before function.';
    }
}