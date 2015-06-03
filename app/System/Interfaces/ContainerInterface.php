<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 14-01-15
 * Time: 12:56
 */

namespace System\Interfaces;


interface ContainerInterface
{

    function available();

    function set($element);

    function get($element);

    function remove($element);

    function asObject();

    function raw();
}