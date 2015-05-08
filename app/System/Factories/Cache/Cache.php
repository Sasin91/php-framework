<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 23-03-15
 * Time: 14:08
 */

namespace System\Factories\Cache;



use Config\Config;

class Cache {

    protected $cache;
    protected $options = array();
    public function __construct($options = array())
    {
        $this->options = $options;
        return $this;
    }

    public function make(array $arguments = array())
    {
        $class = __NAMESPACE__.Config::get('System/Cache')['Factory']['path'];
        return !empty($arguments) ? new $class($arguments) : new $class();
    }

    public function destroy()
    {
        $this->cache = NULL;
    }
}