<?php
namespace System\Factories\Cache\Adapters;

use System\Interfaces\CacheInterface;

if ( ! defined('ROOT_PATH') ) exit('No direct script access allowed');

class Memcache implements CacheInterface {

public function __construct($prefix = 'sfw', $server = array('address' => '127.0.0.1', 'port' => '11211')) {
$this->memcached = new \Memcached;
$cacheAvailable = $this->memcached->addServer($server['address'], $server['port'] );
if(!$cacheAvailable) {throw new \Exception("Could not connect to memcached server");}
$this->prefix = strlen($prefix) > 0 ? $prefix.':' : '';
#var_dump(get_class_methods($this->memcached));
}

	/**
	 * Get the underlying Memcached connection.
	 *
	 * @return \Memcached
	 */
	public function getMemcached()
	{
		return $this->memcached;
	}

	/**
	 * Retrieve an item from the cache by key.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function get($key)
	{
		$value = $this->memcached->get($this->prefix.$key);

		if ($this->memcached->getResultCode() == 0)
		{
			return json_decode($value);
		}
	}

	/**
	 * Store an item in the cache for a given number of minutes.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @param  int     $minutes
	 * @return void
	 */
	public function set($key, $value, $minutes = '60')
	{
		$data = json_encode($value);
		$this->memcached->set($this->prefix.$key, $data, $minutes * 60);
	}

	/**
	 * Increment the value of an item in the cache.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return int|bool
	 */
	public function increment($key, $value = 1)
	{
		return $this->memcached->increment($this->prefix.$key, $value);
	}

	/**
	 * Decrement the value of an item in the cache.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return int|bool
	 */
	public function decrement($key, $value = 1)
	{
		return $this->memcached->decrement($this->prefix.$key, $value);
	}

	/**
	 * Remove an item from the cache.
	 *
	 * @param  string  $key
	 * @return void
	 */
	public function delete($key)
	{
		$this->memcached->delete($this->prefix.$key);
	}

	/**
	 * Remove all items from the cache.
	 *
	 * @return void
	 */
	public function clear()
	{
		$this->memcached->flush();
	}
}