<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 19-04-15
 * Time: 14:09
 */

namespace Http\Interfaces;


interface ControllerInterface {

    public function home($action, array $arguments = array());

}