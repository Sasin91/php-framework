<?php
namespace System\Interfaces;

if (!defined('ROOT_PATH')) exit('No direct script access allowed');

Interface CacheInterface
{
    /**
     *
     * @string $key
     * @mixed $value
     * @integer $lifetime
     *
     * @return mixed
     *
     **/

    public function set($key, $value, $lifetime);

    public function get($key);

    public function delete($key);

    public function clear();

}