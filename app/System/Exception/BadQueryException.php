<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 26-05-15
 * Time: 11:23
 */

namespace System\Exception;


class BadQueryException extends Exception {

    public function __construct($message, $code = 40)
    {
        parent::__construct($message, $code);
    }

}