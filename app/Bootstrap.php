<?php


if ( ! defined('ROOT_PATH') ) exit('No direct script access allowed');

/**
 * Retrieves & Sanitizes the URL then forwards to Router.
 * Class Bootstrap
 */
class Bootstrap
{
    protected $patterns = array(
        '%20' => ' ',
        '%C3%A6' => 'ae',
        '%C3%A5' => 'aa',
        '%C3%B8' => 'oe'
    );

    public function __construct(array $config)
    {
        /**
        * Verify the PHP version.
        * OOP.
        */
       if(!phpversion() >= '5.0.0')
       {
          throw new Exception('Only PHP 5+ supported.');
       }


        new \System\Kernel($config);

        $request_method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET'; //default request_method to get if not set.

        $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : NULL;
        $url = rtrim($url, '/');
        $url = utf8_encode(filter_var($url, FILTER_SANITIZE_URL));
        $url = explode('/', $url);
        array_shift($url);

       if (empty($url[0])) {
            \Http\Router::Route($request_method, $config['Base']['default_url']);
            return;
        }
       $controller = $url[0];
        if(!isset($url[1])) {
            $method = $config['Base']['default_url'];
        } else {
            $method = $url[1];
        }
       $args = array();
       for($i = 1; $i < count($url); $i++)
       {
           $args[] = $this->filter($url[$i]);
           if($request_method == 'POST')
           {
               foreach(filter_var_array($_POST, FILTER_SANITIZE_STRING) as $key => $value)
               {
                   $args['post'][$key] = $this->filter($value);
               }
           }
       }
       \Http\Router::Route($request_method,$controller, $method, $args);
    }

    /**
     * Filter string by pattern, to avoid odd characters.
     * @param $string
     * @return mixed
     */
    private function filter($string)
    {
        $keys = array_keys($this->patterns);
        $values = array_values($this->patterns);
        for($i = 0; $i < count($this->patterns); $i++)
        {
            $string = str_replace($keys[$i], $values[$i], $string);
        }
        return $string;
    }
}
