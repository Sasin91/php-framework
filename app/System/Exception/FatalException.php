<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 26-03-15
 * Time: 11:09
 */

namespace System\Exception;


class FatalException extends Exception
{

    function __construct($message = '', $code = 4, Exception $previous = null)
    {
        if (isset($message)) {
            $msg = $this->fatal_handler($message);
        } else {
            $msg = $this->fatal_handler();
        }
        parent::__construct($msg, $code, $previous);
    }

    private function fatal_handler($msg = '')
    {
        $errfile = "unknown file";
        $errstr = "shutdown";
        $errno = E_CORE_ERROR;
        $errline = 0;

        $error = error_get_last();

        if ($error !== NULL) {
            $errno = $error["type"];
            $errfile = $error["file"];
            $errline = $error["line"];
            $errstr = $error["message"];
        }

        return $this->format_error($errno, $errstr, $errfile, $errline) . $msg;
    }

    private function format_error($errno, $errstr, $errfile, $errline)
    {
        $trace = print_r(debug_backtrace(false), true);

        $content = "<table><thead bgcolor='#c8c8c8'><th>Item</th><th>Description</th></thead><tbody>";
        $content .= "<tr valign='top'><td><b>Error</b></td><td><pre>$errstr</pre></td></tr>";
        $content .= "<tr valign='top'><td><b>Errno</b></td><td><pre>$errno</pre></td></tr>";
        $content .= "<tr valign='top'><td><b>File</b></td><td>$errfile</td></tr>";
        $content .= "<tr valign='top'><td><b>Line</b></td><td>$errline</td></tr>";
        $content .= "<tr valign='top'><td><b>Trace</b></td><td><pre>$trace</pre></td></tr>";
        $content .= '</tbody></table>';

        return $content;
    }
}