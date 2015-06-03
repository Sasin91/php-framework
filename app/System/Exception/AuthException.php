<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 02-06-15
 * Time: 10:24
 */

namespace System\Exception;


class AuthException extends Exception {

    public function __construct($message, $code = '215')
    {
        parent::__construct($message, $code);
    }
}