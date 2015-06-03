<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 23-03-15
 * Time: 14:08
 */

namespace System\Factories\Cache;

class Cache
{

    protected $cache;
    protected $config = array();

    public function __construct($config)
    {
        $this->config = $config;
        return $this;
    }

    public function make(array $arguments = array())
    {
        $class = __NAMESPACE__ . $this->config['Factory']['path'];
        return !empty($arguments) ? new $class($arguments) : new $class();
    }

    public function destroy()
    {
        $this->cache = NULL;
    }
}