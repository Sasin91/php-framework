<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 25-05-2015
 * Time: 17:17
 */

namespace System\Exception;


class FourOhFourException extends Exception {

    public function __construct($message)
    {
        parent::__construct($message, 404);
    }
}