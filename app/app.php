<?php
/**
 * Created by PhpStorm.
 * User: JonasK
 * Date: 12-01-2015
 * Time: 13:34
 */


use System\Authentication\Session;
use System\Factories\Factory;

/**
 * Class app
 */
class app
{

    /**
     * This class represents the application, methods in here will be available by classes which extends from kernel.
     * This class is also a security measurement, only kernel may instantiate it.
     * In a ecoSystemConf, like Linux, this would represent userspace.
     */
    public $kernel;
    public $cache;
    public $db;
    public $config = array();
    private static $instance;
    /**
     * The very kernel of our Application.
     * @param \System\kernel $kernel
     */
    public function __construct(\System\Kernel $kernel)
    {
        $this->config = Config::get('Config');
        $this->kernel = $kernel;
        Session::init();
        static::$instance = $this;
    }

    /**
     * Returns an instance of the database.
     * @param $db
     * @return mixed
     * @throws System\Exception\FilesystemException
     */
    public function newDatabase($db)
    {
        if (is_null($this->db)) {
            $config = $this->config['Database']['Factory'];
            $this->db = Factory::make('Database')->with(array('database' => $db, 'path' => $config['class'], 'config' => $config))->create();
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
        if (is_null($this->cache)) {
            $this->cache = Factory::make('Cache')->config($this->config['Cache'])->create($this->config['Cache']['type']);
        }
        return $this->cache;
    }

    /**
     * Retrieve the base path of our app
     * @return string
     */
    public function path()
    {
        return BASE_PATH . '/' . $this->config['Paths']['sfw_Application'];
    }

    public function config()
    {
        return $this->config;
    }

    public function kernel()
    {
        return $this->kernel;
    }

    public static function baseUrl()
    {
        return static::$instance->config['Base']['url'];
    }

    /**
     * Returns the current URL.
     * @param bool $last
     * @param bool $asString
     * @return array|string
     */
    public static function currentUrl($last = false, $asString = false)
    {
        $link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $array = explode('/', rtrim(htmlspecialchars($link, ENT_QUOTES, 'UTF-8')));

        if ($last === true)
            return basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        if($asString === true)
            return 'http://'.\Toolbox\ArrayTools::array2string($array, '/');

        return $array;
    }

    /**
     * Redirect to a location using a URL path.
     * @param $location
     * @param int $status
     * @return bool
     */
    public static function redirect($location, $status = 302)
    {
        if (!$location)
            return false;
        $url = DS . $location;
        header("Location: $url", true, $status);
    }

    public static function getLoadTime($length = 3)
    {
        return number_format((microtime(true) - App_started), $length);
    }

}