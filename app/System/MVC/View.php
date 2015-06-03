<?php
namespace System\MVC;

use System\LazyLoader;
use System\Traits\Markdown;

if (!defined('ROOT_PATH')) exit('No direct script access allowed');

class View extends Core
{
    use Markdown;

    public $title;
    protected $parsedown;
    private $viewPath = '';

    public function __construct()
    {
        $core = Core::$instance;
        $this->viewPath = $core->app->path() . DS . 'Http/View' . DS;
        $this->title = $core->app->config()['Base']['title'];
        $this->parsedown = LazyLoader::get('ParsedownExtra');
    }

    /**
     * @param $file
     * @param $with
     */
    public $content = array();

    public function render($file, array $with = array(), $path = '', $raw = false, $ext = '.php')
    {
        if (!empty($path)) {
            $this->viewPath = $path;
        } elseif (empty($this->viewPath)) {
            $this->__construct();
        }

        if (!empty($with)) {
            if (!is_array($with)) {
                $opt = compact("with");
            } else {
                $opt = $with;
            }
        } else {
            $opt = array('title' => 'error');
        }

        foreach ($opt as $k => $v) {
            $this->content[$k] = $v;
        }
            require($this->viewPath . '_Templates' . DS . 'header.php');
            if ($raw === true) {
                echo $file;
            } else {
                require($this->viewPath . $file . $ext);
            }
            require_once($this->viewPath . '_Templates' . DS . 'footer.php');
        ob_start();
        ob_get_contents();
    }

    public function renderFeedbackMessages()
    {
        require($this->viewPath . '_Templates' . DS . 'feedback.php');
    }
}
