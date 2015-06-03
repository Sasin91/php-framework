<?php
namespace Http;

use System\Exception\FourOhFourException;
use System\Input\InputHandler;
use System\LazyLoader;
use System\MVC\Controller;

if (!defined('ROOT_PATH')) exit('No direct script access allowed');

class Router
{
    public $patterns = array(
        '{:num}' => '([0-9]+)',
        '{:digit}' => '(\d+)',
        '{:name}' => '(\w+)',
        '{:any}' => '([a-zA-Z0-9\.\-_%]+)',
        '{:all}' => '(.*)',
        '{:module}' => '([a-zA-Z0-9_-]+)',
        '{:namespace}' => '([a-zA-Z0-9_-]+)',
        '{:year}' => '\d{4}',
        '{:month}' => '\d{2}',
        '{:day}' => '\d{2}(/[a-z0-9_-]+)'
    );
    protected $view;
    protected $resourceRoutes = array('index', 'new', 'create', 'show', 'edit', 'update', 'delete');

    protected $routes = array();

    protected $defaults = array();

    protected $controllerArguments = array();
    protected $path = '';

    private $attemptedCall = array();


    public static function Route($action = 'get', $controller = '', $func = '', array $args = array())
    {
        $instance = new static;

        $instance->defaults = array(
            'controller' => 'home',
            'namespace' => __NAMESPACE__ . '\\Controllers\\',
            'func' => 'home'
        );

        $inputClass = new InputHandler($action, $args);

        $instance->controllerArguments = $inputClass->input;

        $func = empty($func) ? $instance->defaults['func'] : $func;

        $controller = empty($controller) ? $instance->defaults['controller'] : $controller;

        $instance->$action($controller, $func);
    }

    /**
     * Shorthand for a route accessed using GET
     *
     * @param string $controller
     * @param object $func The handling function to be executed
     * @return bool
     */
    public function get($controller, $func)
    {
        return $this->match(strtoupper(__FUNCTION__), $controller, $func);
    }

    // Routing patterns

    /**
     * Store a route and a handling function to be executed when accessed using one of the specified methods
     *
     * @param string $methods Allowed methods, | delimited
     * @param string $controller A route pattern such as /about/system
     * @param object $func The handling function to be executed
     * @return bool
     */
    public function match($methods, $controller, $func)
    {
        foreach (explode('|', $methods) as $method) {
            $this->routes[$method][] = array(
                'controller' => $controller,
                'action' => $method,
                'func' => $func
            );
        }
        $this->fire();
    }

    public function fire()
    {
        $this->view = LazyLoader::get('View');

        foreach ($this->routes as $route) {
            for ($i = 0; $i < count($route); $i++) {
                $this->call($route[$i]);
            }
        }
    }

    private function call(array $arguments = array())
    {
        $this->attemptedCall = $arguments;
        $ns = isset($arguments['namespace']) ? $arguments['namespace'] : null;
        $controller = new Controller($this->view);
        if (!isset($ns)) {
            $page = $this->controllerWithoutNS($controller);
        } else {
            $page = $this->controllerWithNS($controller, $ns);
        }
        if (is_object($page)) {
            return $page->route($arguments['func'], $arguments['action'], $this->controllerArguments);
        }
        $this->notFound($arguments);
    }

    private function controllerWithoutNS(Controller $controller)
    {
        return $controller->_switchTo($this->defaults['namespace'], ucfirst($this->attemptedCall['controller']));
    }

    private function controllerWithNS(Controller $controller, $ns)
    {
        return $controller->_switchTo($ns, ucfirst($this->attemptedCall['controller']));
    }

    private function notFound($arguments)
    {
        throw new FourOhFourException($arguments);
    }

    /**
     * Shorthand for a route accessed using POST
     *
     * @param string $controller A route pattern such as /about/system
     * @param object $func The handling function to be executed
     * @return bool
     */
    public function post($controller, $func)
    {
        return $this->match(strtoupper(__FUNCTION__), $controller, $func);
    }

    /**
     * Shorthand for a route accessed using DELETE
     *
     * @param string $controller A route pattern such as /about/system
     * @param object $func The handling function to be executed
     * @return bool
     */
    public function delete($controller, $func)
    {
        return $this->match(strtoupper(__FUNCTION__), $controller, $func);
    }

    /**
     * Shorthand for a route accessed using PUT
     *
     * @param string $controller A route pattern such as /about/system
     * @param object $func The handling function to be executed
     * @return bool
     */
    public function put($controller, $func)
    {
        return $this->match(strtoupper(__FUNCTION__), $controller, $func);
    }

    /**
     * Shorthand for route accessed using patch
     * @param $controller
     * @param $func
     * @return bool
     */
    public function patch($controller, $func)
    {
        return $this->match(strtoupper(__FUNCTION__), $controller, $func);
    }

    /**
     * Shorthand for a route accessed using OPTIONS
     *
     * @param string $controller A route pattern such as /about/system
     * @param object $func The handling function to be executed
     * @return bool
     */
    public function options($controller, $func)
    {
        return $this->match(strtoupper(__FUNCTION__), $controller, $func);
    }

    /**
     * @param $controller
     * @param $func
     * @return bool
     */
    public function any($controller, $func)
    {
        return $this->match('GET|POST|PUT|PATCH|DELETE', $controller, $func);
    }

    /**
     * Set the controller as Resource Controller
     *
     * @param $name
     * @param $controller
     * @return $this
     */
    public function resource($name, $controller)
    {
        foreach ($this->resourceRoutes as $key => $action) {
            $this->{'setResource' . ucfirst($action)}($name, $controller, $action);
        }
        return $this;
    }

    /**
     * Customize the routing pattern using where
     *
     * @param $key
     * @param $controller
     * @return $this
     */
    public function where($key, $controller)
    {
        return $this->setPattern($key, $controller);
    }

    /**
     * @param $key
     * @param $controller
     * @return $this
     */
    private function setPattern($key, $controller)
    {
        $this->patterns[$key] = $controller;
        return $this;
    }

    /**
     * @param $key
     * @return string
     */
    public function getPattern($key)
    {
        return isset($this->patterns[$key]) ? $this->patterns[$key] : '';
    }

    /**
     * Allow you to apply nested sub routing.
     *
     * @param          $groupRoute
     * @param callable $callback
     */
    public function group($groupRoute, \Closure $callback)
    {
        // Track current base path
        $curBaseRoute = $this->path;
        // Build new route base path string
        $this->path .= $groupRoute;
        // Call the Closure callback
        $me = $this; // workaround for $this as lexical.
        call_user_func(function () use ($callback, $me) {
            return $callback($me);
        });
        // Restore original route base path
        $this->routeBasePath = $curBaseRoute;
    }

}
