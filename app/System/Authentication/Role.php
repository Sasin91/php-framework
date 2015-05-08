<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 27-03-15
 * Time: 17:51
 */

namespace System\Authentication;


use Toolbox\ArrayTools;
use Toolbox\StringTools;

class Role extends Auth {

    protected static $granted = array();
    protected static $roles = array(
        'Admin' => 4,
        'Staff' => 3,
        'Moderator' => 2,
        'Member' => 1,
    );

    public function __call($method, $arguments)
    {
        $methods = array();
        $methods[] = get_class_methods($this);
        if(ArrayTools::inArray($methods, $method))
            return static::$method($arguments);
    }

    public static function exist($role)
    {
        return in_array(StringTools::CapitalizeFirst($role), array_keys(static::$roles)) ? true : false;
    }

    public static function getBitMask($role)
    {
        return static::$roles[ucfirst($role)];
    }

    public static function has($role, $name)
    {
        foreach (static::$granted as $key => $value) {
            return $key == $name && $value == $role ? true : false;
        }
        return false;
    }

    public static function grant($role, $name)
    {
        static::$granted[] = array($name => $role);
    }

    public static function remove($role, $name)
    {
        if(static::has($role, $name))
            unset(static::$granted[$name]);
    }

    public static function available()
    {
        return static::$roles;
    }
}