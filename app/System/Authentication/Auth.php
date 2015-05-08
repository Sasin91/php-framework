<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 19-03-15
 * Time: 13:20
 */

namespace System\Authentication;


use Http\Models\User;
use Toolbox\StringTools;
use System\Reflector;
use System\Secrets\Token;

class Auth
{

    private $call;
    private $request;
    private static $type;
    private $objectType;

    public static function what($app_or_user)
    {
        static::$type = $app_or_user;
        return new static;
    }

    public function __call($method, $args = '')
    {
        $this->request = $args;
        $options = array('has', 'get', 'set', 'destroy', 'remove', 'register', 'login');
        return in_array($method, $options) ? $this->setCallAndReturn($method) : 'only these methods are legit: '.$options;
    }

    private function setCallAndReturn($method)
    {
        $this->call = $method;
        return $this;
    }


    public function register($input, $name = '')
    {
        $object = is_array($input) ? $input[0] : $input;
        $this->objectType =  !empty($name) ? $name : $object->type;
        // login process, write the user data into session
        Session::set(static::$type, array(
                 $this->objectType  => $this->populateArrayWith(static::$type, $object),
                ), true
        );
        Role::grant($object->role, $object->label);
        return $this;
    }
    
    private function populateArrayWith($type, $object)
    {
        if(Role::exist($object->role))
        {
            $data = array (
                'authenticated' => true,
                'id' => $object->id,
                'label' => $object->label,
                'role' => $object->role,
                'token' => Token::encrypt($object->label . $object->id)
            );
            if($type == 'user')
                $data['email'] = $object->email;
            return $data;
        }
        return $object;
    }
    /**
     * @return bool
     */
    public function session()
    {
        $method = $this->call;
        if ($method == 'destroy') {
            $this->destroySession();
        } else {
            if(Session::get(static::$type))
            {
                $account = Session::get(static::$type)['Account'];
            } else {
                $account = false;
            }
            if($method == 'get')
            {
                $data = !empty($this->request) ? $account[$this->request[0]] : $account;
            }
            elseif($method != 'has' && $method != 'get')
            {
                $data = Session::$method(static::$type,[$this->request[0]]);
            }
            else {
                $has = !empty($this->request) ? $account[$this->request[0]] : Session::get(static::$type);
                $data = !empty($has) ? true : false;
            }
            return $data;
        }
        return false;
    }

    private $perms;
    public function permission(array $permissions = array())
    {
        if (User::getAccount())
            $this->perms = $this->verify(__FUNCTION__, $permissions);
        if ($this->call == 'has')
            return in_array($permissions['permission'], $this->perms) ? true : false;
        if ($this->call == 'set')
            return !empty($this->rights) ? function () use ($permissions) {
                if (Permission::has('Admin', Session::get('name'))) {
                    if(in_array($permissions['permission'], $this->perms))
                        Permission::grant($permissions['permission'], $permissions['target']);
                }
            } : false;
        return false;
    }

    private $roles;
    public function role(array $roles = array())
    {
        if (User::getAccount())
            $this->roles = $this->verify(__FUNCTION__, $roles);
        if ($this->call == 'has')
            return in_array($roles['role'], $this->roles) ? true : false;
        if ($this->call == 'set')
            return !empty($this->rights) ? function () use ($roles) {
                if (Role::has('Admin', Session::get('name'))) {
                    if(in_array($roles['role'], $this->roles))
                        Role::grant($roles['role'], $roles['target']);
                }
            } : false;
        return false;
    }

    private function verify($type,array $perm_or_role = array())
    {
        $rights = array();
        $ns = __NAMESPACE__.'\\'.StringTools::CapitalizeFirst($type);
        foreach ($perm_or_role as $attempt) {
            $Model = Reflector::reflect($ns);
            if($Model->exist($attempt))
                $rights[] = $attempt;
        }
        return $rights;
    }

    private function destroySession()
    {
        if (Session::destroy()) {
            Session::set("feedback_positive", 'You have been successfully logged out.');
            App::redirect('users/authenticate');
        } else {
            Session::set("feedback_positive", 'You do not have any active session.');
        }
    }
}