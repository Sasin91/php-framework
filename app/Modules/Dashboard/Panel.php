<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 25-05-2015
 * Time: 18:38
 */

namespace Modules\Dashboard;


use System\Authentication\Account;
use System\MVC\View;

class Panel {

    protected $available = array(
        'Admin' => 'Modules\Dashboard\Admin\Dashboard',
        'Staff' => 'Modules\Dashboard\Staff\Dashboard',
        'User'  => 'Modules\Dashboard\User\Dashboard'
    );

    protected $dashboard;

    public function __construct(View $view, $level, Account $user, $token, $url)
    {
        $this->dashboard = new $this->available[$level]($view, $level, $user, $token, $url);
        return $this;
    }

    public function route($method, $arguments)
    {
        return $this->dashboard->$method($arguments[1]);
    }

}