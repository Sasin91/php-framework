<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 18-03-15
 * Time: 10:27
 */

namespace System\Interfaces;


interface ActiveRecord
{

    public function hasMany(array $params = array());

    public function hasOne($params);

    public function belongsToMany(array $objects);

    public function belongsToOne($params);

    public function hasRelationship($parent, $object);

    public function hasTable($params);
}