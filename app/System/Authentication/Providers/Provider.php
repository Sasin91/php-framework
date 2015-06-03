<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 22-12-14
 * Time: 22:47
 */

namespace System\Authentication\Providers;


abstract class Provider
{


    abstract protected function getAuthUrl();


}