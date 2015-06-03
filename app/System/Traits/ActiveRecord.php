<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 14-05-15
 * Time: 01:53
 */

namespace System\Traits;

use System\Containers\ActiveRecordContainer;
use System\Reflector;

trait ActiveRecord
{

    protected $container;

    protected $relations = array();

    private $items = array();
    private $relatedClasses;

    public function hasOne($class)
    {
        $this->noContainer();
        $parent = explode('\\', $this->calling_class());
        array_pop($parent);
        $collection = array();
        if (empty($class))
            return $this->container->get($parent);
        $collection[] = Reflector::reflect($class);

        return $collection;
    }

    public function belongsToOne($object)
    {
        $this->noContainer();
        $class = $this->calling_class();
        $this->container->set($object, array(array_pop(explode('\\', $class)) => $class));
    }

    public function belongsToMany(array $objects)
    {
        $parent = $this->calling_class();
        foreach ($objects as $object) {
            if (!$this->hasRelationship($parent, $object))
                $this->relatedClasses[] = $object;
        }
    }

    public function hasRelationship($parent, $object)
    {
        $this->noContainer();
        $exists = $this->container->exists($object) ? true : false;
        if ($exists)
            if (in_array($parent, $this->relations))
                foreach ($this->relations as $k => $v) {
                    $this->items[] = $v;
                }
        return $this->items;
    }

    public function hasMany(array $classes = array())
    {
        $this->NoContainer();
        $parent = array_pop(explode('\\', $this->calling_class()));
        $collection = array();
        if (empty($classes)) {
            return $this->container->get($parent);
        }

            foreach ($classes as $class) {
                    $collection[$class] = Reflector::reflect($class);
            }
        return $collection;
    }

    public function availableRelations($keysOnly = false)
    {
        return $keysOnly ? array_keys($this->relations) : $this->relations;
    }


    /**
     * Sets ActiveRecordContainer and
     * @return bool
     */
    private function getContainer()
    {
        $this->container = ActiveRecordContainer::_getInstance();
        return true;
    }

    private function NoContainer()
    {
        return is_null($this->container) ? $this->getContainer() : false;
    }
}