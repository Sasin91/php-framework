<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 21-01-15
 * Time: 08:33
 */

namespace System\Exception;


class DatabaseException extends Exception
{

    function __construct($message, $code = 3, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}