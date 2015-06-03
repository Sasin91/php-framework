<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 14-05-15
 * Time: 01:59
 */

namespace System\Traits;

use System\Containers\Collection;

trait hasCollection
{

    /**
     * the collection.
     * @var
     */
    protected $collection;

    /**
     * Find and return the request or create new Collection
     * @param $key
     * @return Collection
     */
    public function findOrNew($key)
    {
        if (is_null($this->collection))
            return $this->newCollection($key);

        $request = $this->find($key);

        if ($request == false)
            return $this->newCollection($key);

        return $request;
    }

    /**
     * Create a new Collection.
     * @param $name
     * @param array $models
     * @return Collection
     */
    public function newCollection($name, array $models = array())
    {
        $this->collection = new Collection($name, $models);
        return $this->collection;
    }

    /**
     * Find and return the requested key.
     * @param $key
     * @return mixed
     */
    public function find($key)
    {
        $request = $this->collection->attributes[$key];
        return !empty($request) ? $request : false;
    }

    public function add($key, $value)
    {
        $this->collection->attributes[$key] = $value;
    }

    /**
     * Return whats in our current Collection.
     * @return mixed
     */
    public function available()
    {
        return $this->collection->attributes;
    }
}