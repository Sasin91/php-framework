<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 24-03-15
 * Time: 10:24
 */

namespace System\Exception;


class CacheException extends Exception
{


    function __construct($message, $code = 404, Exception $previous = null)
    {
        $msg = $message . ' was not found in cache.';
        parent::__construct($msg, $code, $previous);
    }
}