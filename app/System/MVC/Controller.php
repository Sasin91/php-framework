<?php
namespace System\MVC;

use System\Authentication\Session;
use System\Exception\BadMethodCallException;
use System\LazyLoader;

if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

class Controller extends Core {

    // We define protected static variables for the classes & MVC for that matter, that should be accessible from child classes.
    protected $BaseModel;
    protected $view;
    protected $model;
    protected $availableMethods = array();

    function __construct() {
        parent::__construct();
        $this->view = LazyLoader::get('View');
    }

    public function route($method, $action, array $args = array())
    {
        return $this->$method($action, $args);
    }

    public function __call($method, $arguments)
    {
        foreach (get_class_methods($this) as $method) {
            $this->availableMethods[] = $method;
        }
        if(!in_array($method, $this->availableMethods))
        {
            throw new BadMethodCallException($method);
        } else {
            // @TODO: Permission verification
            return $this->$method;
        }
    }

    public static function _switchTo($ns, $name)
    {
            if (!empty($ns)) {
                $controller = $ns . $name;
                if(class_exists($controller))
                    return new $controller();
            }
            return false;
    }
}
