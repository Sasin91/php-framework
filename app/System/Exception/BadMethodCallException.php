<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 25-01-15
 * Time: 02:43
 */

namespace System\Exception;


class BadMethodCallException extends Exception
{

    function __construct($message, $code = 5, Exception $previous = null)
    {
        $msg = 'The method, {' . $message . '}, you requested is either unavailable or does not exist.';
        parent::__construct($msg, $code, $previous);
    }

}