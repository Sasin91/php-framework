<?php

namespace System\MVC;

use Config\Config;
use app;
use Modules\Generators\Generator;
use System\Kernel;

abstract class Core extends Kernel
{
    protected $auth;
    protected $messages;
    protected $feedback;
    protected $layout;
    protected $env;
    protected $app;
    protected $cache;



    public function __construct() {
        parent::__construct();
        $this->init();
    }

    public function init()
    {
        $this->app = new app($this);
        $this->cache = $this->app->cache('File');
        $this->setEnviroment(Config::get('System/Config')['Base']['enviroment']);
        $this->feedback = Config::get('System/Messages')['feedback'];
        $this->layout = Generator::make('Layout')->create();

    }


    private function setEnviroment($enviroment)
    {
        $this->env = $enviroment;
        date_default_timezone_set('Europe/Copenhagen'); // @TODO: find a way to get this from Configurations (Note, need to append '').
        if($enviroment == 'Development'):
        error_reporting( E_ALL ); // set error handling reporting
        ini_set('display_errors','On'); // Make our framework show errors, this is in many frameworks defined as Development or Production mode.
        ini_set('log_errors', 'On'); // Lets make sure we actually do log anything that happens.
        ini_set('error_log', ROOT_PATH . DS . 'logs' . DS . 'error.' . date('m-d-Y') . '.txt'); // Lets make it easy to navigate our log files by date, as one huge file is well, Huge in no time at all.
       # register_shutdown_function( "shutdown" );
        else:
        error_reporting( E_NOTICE ); // set error handling reporting
        ini_set('display_errors','Off'); // Make our framework show errors, this is in many frameworks defined as Development or Production mode.
        ini_set('log_errors', 'On'); // Lets make sure we actually do log anything that happens.
        ini_set('error_log', ROOT_PATH . DS . 'logs' . DS . 'error.' . date('m-d-Y') . '.txt'); // Lets make it easy to navigate our log files by date, as one huge file is well, Huge in no time at all.
        endif;
    }
    }
