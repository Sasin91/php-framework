<?php

namespace Http\Controllers;

use \app;
use Http\Models\User;
use Modules\Dashboard\Panel;
use System\Authentication\Auth;
use System\LazyLoader;
use System\MVC\Controller;

if (!defined('ROOT_PATH')) exit('No direct script access allowed');

class Dashboard extends Controller
{
    protected $level;
    protected $user;
    protected $label;
    protected $role;
    protected $auth;
    protected $panel;
    protected $token;

    public function __construct()
    {
        $this->auth = Auth::what('user');
        if (!$this->auth->has('authenticated')->session()) {
            return app::redirect('users');
        }

        $this->user = User::getAccount();
        $this->label = $this->user->username;
        $this->role = User::getRole();
        $this->token = $this->auth->get('token')->session();

        if(is_null($this->view))
        {
            $this->view = LazyLoader::get('View');
        }

        $this->panel = new Panel($this->view, $this->role, $this->user, $this->token, app::currentUrl());
    }

    public function __call($method, $arguments)
    {
        if($this->token)
        {
            $this->panel->route($method, $arguments);
        }
    }
}