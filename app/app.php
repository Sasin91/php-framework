<?php
/**
 * Created by PhpStorm.
 * User: JonasK
 * Date: 12-01-2015
 * Time: 13:34
 */


use System\Authentication\Session;
use System\Factories\Factory;
use Config\Config;

/**
 * Class app
 */
class app {

    /**
     * This class represents the application, methods in here will be available by classes which extends from Core.
     * This class is also a security measurement, only Core may instantiate it.
     * In a ecoSystemConf, like Linux, this would represent userspace.
     */
    protected $Core;
    protected $cache;
    protected $db;
    protected $config = array();

    /**
     * The very core of our Application.
     * @param \System\MVC\Core $Core
     */
    public function __construct(\System\MVC\Core $Core)
    {
        $this->config = Config::get('System/Config');
        $this->Core = $Core;
        define('App_loaded', microtime(true));
        Session::init();
    }

    /**
     * Returns an instance of the database.
     * @param $db
     * @return mixed
     * @throws System\Exception\FilesystemException
     */
    public function newDatabase($db)
    {
        if(is_null($this->db)) {
            $config = $this->config['Database']['Databases'];
            $this->db = Factory::make('Database')->with(array('database' => $db, 'path' => $config['Factory']['path'], 'config' => $config))->create();
        }
        return !is_null($this->db) ? $this->db : $this->newDatabase($db);
    }

    /**
     * Returns an instance of the cache.
     * @return mixed
     * @throws System\Exception\FilesystemException
     */
    public function cache()
    {
        if(is_null($this->cache)) {
            $this->cache = Factory::make('Cache')->create($this->config['Cache']['type']);
        }
        return $this->cache;
    }

    /**
     * Retrieve the ROOT_PATH path of our app
     * @return string
     */
    public function path()
    {
        return ROOT_PATH.'/'.$this->config()->fetch()->Paths->sfw_Application;
    }

    /**
     * Returns the current URL.
     * @return string
     */
    public static function currentUrl($last = false)
    {
        if($last === true)
        {
            return basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        }
        $link =  "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        return explode('/',rtrim(htmlspecialchars($link, ENT_QUOTES, 'UTF-8')));
    }

    /**
     * Redirect to a location using a URL path.
     * @param $location
     * @param int $status
     * @return bool
     */
     public static function redirect($location, $status = 302) {
        if ( !$location )
            return false;
         $url = DS.$location;
        header("Location: $url", true, $status);
    }
}