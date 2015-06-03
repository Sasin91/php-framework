<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 25-05-2015
 * Time: 19:23
 */

namespace Modules\Dashboard;


use System\MVC\View;

class BaseDashboard {

    protected $view;

    protected $level;

    protected $user;

    protected $token;

    protected $url;

    public function __construct(View $view, $level, $user, $token, $url)
    {
        $this->view = $view;

        $this->level = $level;

        $this->user = $user;

        $this->token = $token;

        $this->url = $url;
    }

    public function home()
    {
        new Home($this->view, $this->level, $this->user, $this->url);
    }


}