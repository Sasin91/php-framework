<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 19-03-15
 * Time: 14:16
 */

namespace System\Authentication;


use Toolbox\ArrayTools;
use Toolbox\StringTools;

class Permission extends Auth {

   protected static $granted = array();
   protected static $permissions = array(
       'Read',
       'Write',
       'Edit',
       'Create',
       'Delete'
   );

    protected static $predefinedPermissions = array(
        'Member' => 'Read',
        'Moderator' => 'Create',
        'Staff' => 'Write, Edit, Delete',
        'Admin' => '*'
    );

    public function __call($method, $arguments)
    {
        $methods = array();
        $methods[] = get_class_methods($this);
        if(ArrayTools::inArray($methods, $method))
            return static::$method($arguments);
    }


    public static function exist($permission)
    {
        return in_array(StringTools::CapitalizeFirst($permission), static::$permissions) ? true : false;
    }

    public static function has($permission, $name)
    {
        foreach (static::$granted as $key => $value) {
            return $key == $name && $value == $permission ? true : false;
        }
        return false;
    }

    public static function grant($permission, $name)
    {
        #$role = Role::has(ArrayTools::getFirstIn(Auth::what('')))
        static::$granted[] = array($name => $permission);
    }

    public static function remove($permission, $name)
    {
        if(static::has($permission, $name))
            unset(static::$granted[$name]);
    }

    public static function available()
    {
        return static::$permissions;
    }

}