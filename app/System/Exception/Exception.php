<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 20-01-15
 * Time: 14:20
 */

namespace System\Exception;

use Config;
use System\LazyLoader;

class Exception extends \Exception
{

    private $view;
    private $env;
    private $previous;

    public function __construct($message, $code = 500, Exception $previous = null)
    {
        if (!is_null($previous)) {
            $this->previous = $previous;
        }
        $this->env = Config::get('Config')['Base'];
        $this->view = LazyLoader::get('View');
        $this->show($code, $message);
    }

    // custom string representation of object
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function show($code, $message)
    {
        http_response_code($code);
        $title = 'There was an error, thats all we know.';
        $trace = self::getTraceAsString();
        $this->view->render('error', compact('title', 'code', 'message', 'trace'));
    }
}