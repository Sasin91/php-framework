<?php
namespace Http\Controllers;

use \app;
use Http\Models\User;
use System\Authentication\Auth;
use System\Authentication\Providers\Provider;
use System\Exception\AuthException;
use System\Reflector;

if (!defined('ROOT_PATH')) exit('No direct script access allowed');

class Users extends BaseController
{

    protected $model;
    protected $request_method;

    public function home($action, $argument)
    {
        if (Auth::check()) {
            return new Dashboard();
        } else {
            $this->login();
        }

    }

    public function login($action = 'GET', $input = '')
    {
        if (Auth::check()) return app::redirect('dashboard');

        if (strip_tags($action == 'GET')) {
                $this->view->render('Login/index', array('title' => 'Authenticate'));

        } elseif (strip_tags($action == 'POST')) {

            if(!empty($input->arguments))
            {
                $args = $input->arguments['post'];
            } else {
                throw new AuthException('Invalid data given for login@Users');
            }

            #$type = strip_tags($_POST['type']); // Ommited for now, no direct use.
            if(isset($args['username'])) {
                $data['username'] = strip_tags($args['username']);
            }
                if(isset($args['email'])) {
                $data['email'] = strip_tags($args['email']);
            }
                $data['psw'] = strip_tags($args['password']);
            $instance = Reflector::reflect('System\Authentication\Providers\\' . strip_tags('System'));
            $this->doLogin($instance, $data);
        }
    }

    private function doLogin(Provider $provider, $user)
    {
        $provider->authenticate($user, New User());
    }

    private $data;

    function register($action, $args)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->view->render('Login/create', array('title' => 'Opret Bruger'));
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->data  = $args->get('post');

            $this->data['joindate'] = date('y-m-d h:m:s');
            $encryption = \Config::get('Config')['User']['Encryption'];
            $hashCost = $encryption['BcryptHastCost'];
            $this->data['pass'] = password_hash($this->data['password'], PASSWORD_DEFAULT, array('cost' => $hashCost));
            $this->data['password'] = true;

            $provider = Reflector::reflect('System\Authentication\Providers\\' . $this->data['type']);

            $this->createAccount($provider, $this->data);
        }
    }

    private function createAccount(Provider $provider, $user)
    {
        return $provider->Create($user, new User());
    }

    public function logout()
    {
        User::logout();
    }
}