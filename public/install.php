<?php
include_once 'index.php';

class Install {

    private $config = array();
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->database();
    }

    private function database()
    {
        chdir(ROOT_PATH . DS . 'Server Configuration/Database');
        foreach ($this->config['Database']['Databases'] as $database => $credentials) {
            if(is_file($database.'.sql'))
            {
                //@TODO: PDO Insert
            }
        }
        exit;

    }

    private function apache2()
    {

    }

    private function nginx()
    {

    }

    private function hosts()
    {

    }

    private function geoIP()
    {

    }
}