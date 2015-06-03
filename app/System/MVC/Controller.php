<?php
namespace System\MVC;

use System\Exception\BadMethodCallException;
use System\Exception\FourOhFourException;
use System\Input\Input;
use System\Reflector;

if (!defined('ROOT_PATH')) exit('No direct script access allowed');

class Controller extends Core
{

    //Define protected variables for the classes & MVC for that matter, that should be accessible from child classes.
    protected $view;
    protected $core;
    protected $kernel;
    public $messages;

    /**
     * Inject class View and pass it to children.
     * @param View $view
     */
    function __construct(View $view)
    {
        $this->core = Core::$instance;
        $this->kernel = $this->core->app->kernel();
        $this->messages = $this->core->messages;
        $this->view = $view;
    }

    /**
     * @param $ns
     * @param $name
     * @return mixed
     * @throws FourOhFourException
     */
    public function _switchTo($ns, $name)
    {
        if (!empty($ns)) {
            $controller = $ns . $name;
            if (class_exists($controller)) {
                    /**
                     * First check if we can get a reflection (99.9% of all cases) as it's a LOT lighter.
                     */
                    $reflection = Reflector::reflect($controller, $this->view);
                    if(is_object($reflection))
                        return $reflection;

                    /**
                     * Else, create a new instance.
                     */
                    $class = new $controller($this->view);
                    if(is_object($class))
                        return new $controller($this->view);

                    // else
                    $this->view->render('error', array('message' => 'Failed to instantiate '.$controller));
            }
            throw new FourOhFourException('What is a '.$controller.' ? :)');
        }
        throw new FourOhFourException('No such namespace '.$ns);
    }

    /**
     * @param $method
     * @param $action
     * @param Input $args
     * @return mixed
     */
    public function route($method, $action, Input $args)
    {
        return $this->$method($action, $args);
    }

    public function __call($method, $arguments)
    {
        throw new FourOhFourException('<b>'.$method.'()</b> Not found in '.__CLASS__);
    }
}
