<?php

namespace System\MVC;

use System\Interfaces\Messages;
use System\Output\LogHandler;
use System\Traits\canBacktraceParent;

class Core implements Messages
{
    /**'
     * Is this class instantiated?
     * @var bool
     */
    public static $booted = false;

    /**
     * A stored instance of System\MVC\Core
     * @var
     */
    public static $instance;

    /**
     * Associative array containing messages for communication between classes
     * @array
     */
    public $messages = array();


    protected $feedback;
    protected static $env;
    protected $cache;
    protected $app;

    use canBacktraceParent;
    /**
     * @param \app $app, app works like a glue between kernel and 'userspace'
     * @param array $config
     */
    public static function _init(\app $app, array $config)
    {
        $instance = new static;
        $instance->boot(
            $app,
            $config['Base']['timezone'],
            $config['Base']['enviroment'],
            $config['Messages']['feedback']
        );
    }

    public function boot(\app $app, $timezone, $env, $feedback)
    {
        $this->setEnviroment(ucfirst($env), $timezone);
        $this->app = $app;
        $this->cache = $app->cache();
        $this->feedback = $feedback;
        static::$booted = true;
        define('App_started', microtime(true));
        static::$instance = $this;
    }

    private function setEnviroment($enviroment, $timezone)
    {
        static::$env = $enviroment;
        date_default_timezone_set($timezone);
        if ($enviroment == 'Development'):
            error_reporting(E_ALL); // set error handling reporting
            ini_set('display_errors', 'On'); // Make our framework show errors, this is in many frameworks defined as Development or Production mode.
            ini_set('log_errors', 'On'); // Lets make sure we actually do log anything that happens.
            ini_set('error_log', LogHandler::getOrNew('error', true)); // Lets make it easy to navigate our log files by date, as one huge file is well, Huge in no time at all.
        else:
            error_reporting(E_NOTICE); // set error handling reporting
            ini_set('display_errors', 'Off'); // Make our framework show errors, this is in many frameworks defined as Development or Production mode.
            ini_set('log_errors', 'On'); // Lets make sure we actually do log anything that happens.
            ini_set('error_log', LogHandler::getOrNew('error', true)); // Lets make it easy to navigate our log files by date, as one huge file is well, Huge in no time at all.
        endif;
        LogHandler::writeAccessLog();
    }

    public static function getEnviroment()
    {
        return static::$env;
    }

    public static function getConfig()
    {
        return Core::$instance->app->config;
    }

    /**
     * Parse the message
     * @param string $receiver
     * @param $message
     */
    public function parseMessage($receiver = '', $sender, $message)
    {
        if(empty($receiver))
        {
            $receiver = '*';
        }

        switch($message):

            case is_object($message):
                $this->handleMessage('object', $sender, $receiver, $message);
                break;

            case is_array($message):
                $this->handleMessage('array', $sender, $receiver, $message);
                break;

            case is_int($message):
                $this->handleMessage('int', $sender, $receiver, $message);
                break;

            case is_bool($message):
                $this->handleMessage('bool', $sender, $receiver, $message);
                break;

            default:
                $this->handleMessage('string', $sender, $receiver, $message);
                break;

        endswitch;

    }

    /**
     * Handle that message.
     */
    public function handleMessage($type, $sender, $receiver, $message)
    {
        if(!empty($this->messages[$receiver]))
        {
            array_push($this->messages[$receiver], compact('type', 'message'));
        } else {
            $this->messages[$receiver] = compact('type', 'message');
        }
        $this->forwardMessage($receiver, $this->messages[$receiver]);
    }

    protected function forwardMessage($receiver, array $messages)
    {
        $instance = static::$instance->app->kernel()->load($receiver);
        $instance->messages = $messages;
    }

}
