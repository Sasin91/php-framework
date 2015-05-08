<?php
namespace System\Authentication;


if ( ! defined('ROOT_PATH') ) exit('No direct script access allowed');

class Session {

    /**
     * Initiates the PHP Session.
     */
    public static function init()
    {
        if(session_id() == '')
        {
            session_start();
        }
    }

    /**
     * Returns the entire $_SESSION.
     * @return mixed
     */
    public static function available()
    {
        return $_SESSION;
    }

    /**
     * registers an array into $_SESSION.
     * @param array $data
     */
    public static function register(array $data = array())
    {
       array_walk_recursive($data, function($value, $key){
           static::set($key, $value);
       });
    }

    /**
     * Set a session variable.
     * @param array $data
     */
    public static function set($key, $data, $profile = false)
    {
        if($profile) {
             return $_SESSION[$key] = $data;
        }
        if(!empty($_SESSION[$key])) {
                if(is_array($data))
                {
                    $keys = array_keys($data);
                    $values = array_values($data);
                    for($i = 0; $i < count($values); $i++)
                    {
                        $item = array_keys($values[$i]);
                       return $_SESSION[$key][$keys[0]][$item[$i]] = $values[$i][$item[$i]];
                    }
                    return $_SESSION[$key];
                }
              return $_SESSION[$key] = $data;
            }
        return $_SESSION[$key] = $data;
    }

    /**
     * If exist, returns value.
     * @param $key String | Array
     * @return @var
     */
    public static function get($key)
    {
        $request = '';
        if(is_array($key)) {
            foreach ($key as $request) {
                if (isset($_SESSION[$request]))
                    return $_SESSION[$request];
            }
        } else {
            $request = $key;
        }

        if (isset($_SESSION[$request]))
            return $_SESSION[$request];
        return false;
    }

    /**
     * If exist, removes key(s) from Session.
     * @param $parent
     * @param $key
     */
    public static function remove($parent, $key)
    {

        if(is_array($key)){
            $keys = array_keys($key);
            $values = array_values($key)[0];
            for($i = 0; $i < count($keys); $i++)
            {
                if(is_array($parent))
                {
                    $first = $parent[0];
                } else {
                    $first = $parent;
                }
                if(!empty($_SESSION[$first][$keys[$i]][$values[$i]]->qty))
                {
                    if($_SESSION[$first][$keys[$i]][$values[$i]]->qty <= 0)
                    {
                        unset($_SESSION[$first][$keys[$i]][$values[$i]]);
                    } else {
                        $_SESSION[$first][$keys[$i]][$values[$i]]->qty = $_SESSION[$first][$keys[$i]][$values[$i]]->qty-1;
                    }
                } else {
                    if($i === 0)
                    {
                      unset($_SESSION[$first][$key[0]]);
                    }
                    unset($_SESSION[$first][$keys[$i]][$values[$i]]);
                }
            }

        } else {
            if (!empty($parent)) {
                unset($_SESSION[$parent][$key]);
            } else {
                unset($_SESSION[$key]);
            }
        }
    }


    /**
     * Destroys the $_SESSION and everything that inhibits.
     * @return bool
     */
    public static function destroy()
    {
        return session_destroy();
    }

}
