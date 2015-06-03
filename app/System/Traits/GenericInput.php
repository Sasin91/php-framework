<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 21-05-15
 * Time: 21:48
 */

namespace System\Traits;


trait GenericInput {

    public $arguments = array();

    public function __construct($args)
    {
        $this->arguments = $args;
    }

    public function get($arg)
    {
        $response = $this->arguments[$arg];
        return isset($response) ? $response : false;
    }

    public function available()
    {
        return $this->arguments;
    }

}