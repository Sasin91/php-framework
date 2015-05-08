<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 20-04-15
 * Time: 17:07
 */

namespace Http\Models;


use System\Models\Auth;
use System\Models\Role;
use System\MVC\Model;

class User extends Model {

    protected $database = 'Auth';
    public function __construct()
    {
        parent::__construct(array(
            'label', 'email', 'psw', 'ip', 'location', 'joindate', 'image', 'info', // users table
            'uid', 'role', 'position' // members table
        ));
    }

    /**
     * returns bool on user having an active Session or not.
     * @return mixed
     */
    public static function Authenticate()
    {
        return Auth::what('user')->has('authenticated')->session();
    }

    /**
     * returns active user's account from session.
     * @return mixed
     */
    public static function getAccount()
    {
        return Auth::what('user')->get()->session();
    }

    /**
     * Returns role or bitMask of active user.
     * @param bool $bitMask
     * @return mixed
     */
    public static function getRole($bitMask = false)
    {
        $role = Auth::what('user')->get('role')->session();
        return $bitMask === true ? Role::getBitMask($role) : $role;
    }

    /**
     * Returns the current Account token.
     * @return mixed
     */
    public static function getToken()
    {
        return Auth::what('user')->get('token')->session();
    }

    public static function logout()
    {
        // set the remember-me-cookie to ten years ago (3600sec * 365 days * 10).
        // that's obviously the best practice to kill a cookie via php
        // @see http://stackoverflow.com/a/686166/1114320
        setcookie('rememberme', false, time() - (3600 * 3650), '/', COOKIE_DOMAIN);

        // delete the session
        Auth::what('user')->destroy()->session();
    }
}