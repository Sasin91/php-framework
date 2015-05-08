<?php
namespace Http\Controllers;

use Http\Models\User;
use Core\app;
use System\Authentication\Providers\Provider;
use System\Reflector;

if ( ! defined('ROOT_PATH') ) exit('No direct script access allowed');

class Users extends BaseController {

    protected $model;
	protected $request_method;

	public function __construct() {
        parent::__construct();
        $this->model = new User();
   	}

    public function home($action, array $argument = array())
    {
        $this->request_method = $action;
        if(!empty($argument[0])) {
          return $this->$argument[0]();
        } else {
            $this->authenticate();
        }

    }

    public function authenticate()
    {
        if(User::Authenticate()) return app::redirect('dashboard');
        if(strip_tags($_SERVER['REQUEST_METHOD'] == 'GET')) {
            $this->view->render('Login/index', array('title' => 'Authenticate'));
        }
        elseif(strip_tags($_SERVER['REQUEST_METHOD'] == 'POST')) {
            $type = strip_tags($_POST['type']);
            $data['username'] = strip_tags($_POST['username']);
            $data['email'] =  strip_tags($_POST['email']);
            $data['psw'] = strip_tags($_POST['password']);
            $instance = Reflector::reflect('System\Authentication\Providers\\' . strip_tags($type));
            $this->doLogin($instance, $data);
        }
    }

    private function doLogin(Provider $provider, $user)
    {
        $provider->authenticate($user, $this->model);
    }

    private $data;
    function create()
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->view->render('Login/create', array('title' => 'Opret Bruger'));
        }
        elseif($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type = strip_tags($_POST['type']);
            $this->data['email'] =  strip_tags($_POST['email']);
            $this->data['password'] = strip_tags($_POST['password']);
            $this->data['url'] = strip_tags($_POST['url']);
            $inst = Reflector::reflect('System\Authentication\Providers\\'.$type);
            if(preg_match('/^OAuth/', $type)){
                substr($type, 5); // Oauth doesn't need the additional data.
                return $this->doCreate($inst, $this->data);
            }
            $this->getAdditionalData($this);
            $this->doCreate($inst, $this->data);
        }
    }

    private function doCreate(Provider $provider, $user)
    {
        return $provider->Create($user, $this->model);
    }

	private function getAdditionalData(self $controller)
    {
                $this->data['username'] = strip_tags($_POST['username']);
                $this->data['joindate'] = date('y-m-d h:m:s');
                $encryption = $this->app->config()->fetch('file', 'Encryption');
                $hashExpense[] = $encryption['BcryptHastCost'];
                $this->data['pass'] = password_hash($this->data['password'], PASSWORD_DEFAULT, $hashExpense);
                    return $this->data;
    }

    public function logout() {
        User::logout();
    }
}