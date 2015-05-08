<?php

namespace Http\Controllers;


use Core\Http\Models\Layout\Page;
use Core\Http\Toolbox\StringTools;

class Home extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }

    public function home($action, array $arguments = array())
    {
        $content = array(
            'title' => 'Home',
            );
        $this->view->render('Home/index', $content);
    }
}