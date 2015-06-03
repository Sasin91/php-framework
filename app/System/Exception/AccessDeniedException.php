<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 05-05-15
 * Time: 14:05
 */

namespace System\Exception;


class AccessDeniedException extends Exception
{

    public function __construct($message)
    {
        parent::__construct($message, 7);
    }
}