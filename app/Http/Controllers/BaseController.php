<?php
namespace Http\Controllers;
use Http\Interfaces\ControllerInterface;
use Modules\Generators\Layout\Layout;
use Modules\Generators\Layout\Table;
use System\MVC\Controller;

if ( ! defined('ROOT_PATH') ) exit('No direct script access allowed');
class BaseController extends Controller implements ControllerInterface
{
	protected $container;
	public function __construct() {
		parent::__construct();
	}

    public function home($action, array $arguments = array())
    {
        $content = '#welcome!
        this is the default method home,
        which means you have not defined a home method in your controller! :)';
        $this->view->render('_Templates/placeholder', compact('content', 'action', 'arguments'));
    }
}