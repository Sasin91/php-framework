<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 24-03-15
 * Time: 13:40
 */

namespace System\Exception;


use Core\Application\Debug\Debug;

class FactoryException extends Exception
{

    function __construct($message, $code = 4, Exception $previous = null)
    {
        parent::__construct($message . '<br>' . $this->generateCallTrace(), $code, $previous);
    }

    function generateCallTrace()
    {
        $trace = explode("\n", $this->getTraceAsString());
        // reverse array to make steps line up chronologically
        $trace = array_reverse($trace);
        array_shift($trace); // remove {main}
        array_pop($trace); // remove call to this method
        $length = count($trace);
        $result = array();

        for ($i = 0; $i < $length; $i++) {
            $result[] = ($i + 1) . ')' . substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
        }

        return "\t" . implode("\n\t", $result);
    }
}