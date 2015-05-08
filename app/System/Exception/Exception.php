<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 20-01-15
 * Time: 14:20
 */

namespace System\Exception;

use System\Authentication\Session;
use System\LazyLoader;
use Config\Config;
use System\MVC\View;

class Exception extends \Exception {

    private $view;
    private $env;
    private $previous;

    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null) {
        if (!is_null($previous))
        {
            $this->previous = $previous;
        }
        $this->env = Config::get('System\Config')['Base'];
        $this->view = new View();
        $this->show($code, $message);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function show($code, $message) {
            http_response_code(500);
            $title = 'There was an error, thats all we know.';
            View::render('error', compact('title', 'code', 'message'));
    }
}