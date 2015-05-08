<?php
namespace System\MVC;


use System\Containers\ObjectContainer;
use System\LazyLoader;
use System\Reflector;
use System\Traits\Markdown;

if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

class View extends Core
{
    use Markdown;

    protected $parsedown;
    private $viewPath = '';

    public function __construct()
    {
        parent::__construct();
        $this->viewPath = $this->app->path() . DS . 'View' . DS;
        $this->parsedown = LazyLoader::get('ParsedownExtra');
    }

    /**
     * Allows for calling View::Render();
     * @param $file
     * @param array $arguments
     */
    public static function __callStatic($file, array $arguments)
    {
        $instance = new static;
        $instance->render($file, $arguments);
    }

    /**
     * @param $file
     * @param $with
     */
    public $content;
    public function render($file,array $with = array(), $path = '', $raw = false, $ext = '.php')
    {
        if(empty($path))
        {
            if(empty($this->viewPath))
            {
                $this->__construct();
            }
            $path = $this->viewPath;
        }
        if(!empty($with)) {
            if (!is_array($with)) {
                $opt = compact("with");
            } else {
                $opt = $with;
            }
        } else {
         $opt = array('title' => 'error');
        }
        $this->content = new ObjectContainer();
        $this->content->create('View');
        foreach ($opt as $k => $v) {
            $this->content->set($k, $v);
        }
            require_once($this->viewPath . '_Templates' . DS . 'header.php');
            if($raw === true)
            {
                echo $file;
            } else {
                require_once($path . $file . $ext);
            }
           # require_once($this->viewPath . '_Templates' . DS . 'footer.php');
        ob_start();
        ob_get_contents();
    }

    function getLoadTime($length = 3)
    {
        if(defined(App_loaded))
            return number_format((microtime(true) - microtime(true)), $length);
        return number_format(microtime(true));
    }

    public function renderFeedbackMessages()
    {
        require($this->viewPath . '_Templates' . DS . 'feedback.php');
    }
}
