<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 19-03-15
 * Time: 13:20
 */

namespace System\Authentication;


use Http\Models\User;
use System\Containers\AccountHandler;
use System\Reflector;
use System\Secrets\Token;
use Toolbox\StringTools;

class Auth
{

    private $call;
    private $request;
    private static $type;

    protected $accounts;
    protected $token;

    public static function what($app_or_user)
    {
        $instance = new static;
        if(!is_object($instance->accounts))
        {
            $instance->boot();
        }
        static::$type = $app_or_user;
        return $instance;
    }

    protected function boot()
    {
        $this->accounts = new AccountHandler();
        $this->token = new Token();
    }

    /**
     * Quick way to verify User session.
     * @return mixed
     */
    public static function check()
    {
        $instance = new static;
        $instance->call = 'has';
        static::$type = 'user';
        return $instance->session();
    }

    public function __call($method, $args = '')
    {
        $this->request = $args;
        $options = array('has', 'get', 'set', 'destroy', 'remove', 'login');
        return in_array($method, $options) ? $this->setCallAndReturn($method) : 'only these methods are legit: ' . $options;
    }

    private function setCallAndReturn($method)
    {
        $this->call = $method;
        return $this;
    }


    public function register($input, $name = '')
    {
        $object = $this->accounts->newAccount(new Account($input));
        if(is_object($object))
        {
            $object->authenticated = true;
            $object->token = $this->token->encrypt($object->username . $object->id);
        }

        $type = !empty($name) ? $name : $object->type;
        // login process, write the user data into session
        if(Role::exist($object->role))
            Session::set(static::$type, array(
                    $type => $object,
                ), true
            );
        Role::grant($object->role, $object->username);
        return $this;
    }

    /**
     * @return bool
     */
    public function session()
    {
        $method = $this->call;
        if(!empty($this->request))
        {
            $req = $this->request[0];
        }
        if ($method == 'destroy') {
            $this->destroySession();
        } else {
            if (Session::get(static::$type)) {
                $account = Session::get(static::$type)['Account'];
            } else {
                $account = false;
            }
            if ($method == 'get') {
                $data = !empty($req) ? $account->$req : $account;
            } elseif ($method != 'has' && $method != 'get') {
                $data = Session::$method(static::$type, $this->request[0]);
            } else {
                if($account)
                {
                    $has = !empty($req) ? $account->$req : Session::get(static::$type);
                } else {
                    return false;
                }
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
                    if (in_array($permissions['permission'], $this->perms))
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
                    if (in_array($roles['role'], $this->roles))
                        Role::grant($roles['role'], $roles['target']);
                }
            } : false;
        return false;
    }

    private function verify($type, array $perm_or_role = array())
    {
        $rights = array();
        $ns = __NAMESPACE__ . '\\' . StringTools::CapitalizeFirst($type);
        foreach ($perm_or_role as $attempt) {
            $Model = Reflector::reflect($ns);
            if ($Model->exist($attempt))
                $rights[] = $attempt;
        }
        return $rights;
    }

    private function destroySession()
    {
        if (Session::destroy()) {
            Session::set("feedback_positive", 'You have been successfully logged out.');
            \app::redirect('users/login');
        } else {
            Session::set("feedback_positive", 'You do not have any active session.');
        }
    }
}