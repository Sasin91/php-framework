<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 24-03-15
 * Time: 09:21
 */

namespace System\Exception;


class SessionException extends Exception
{

    function __construct($message, $code = 404, Exception $previous = null)
    {
        $msg = $message . ' was not found in session.';
        parent::__construct($msg, $code, $previous);
    }
}