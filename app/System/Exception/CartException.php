<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 21-04-15
 * Time: 17:30
 */

namespace System\Exception;


class CartException extends Exception {

    function __construct($message, $code = 5, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}