<?php
namespace Http\Controllers;

use System\MVC\Controller;

if (!defined('ROOT_PATH')) exit('No direct script access allowed');

class BaseController extends Controller
{
    /**
     * Default method, if nothing else is defined in child.
     * @param $action
     * @param $arguments
     */
    public function home($action, $arguments)
    {
        $content = '#Welcome!
        this is the default method home,
        which means you have not defined a home method in your controller! :)';
        $this->view->render('_Templates/placeholder', compact('content', 'action', 'arguments'));
    }

    public function phpinfo()
    {
        $this->view->render('_Templates/placeholder', phpinfo());
    }
}