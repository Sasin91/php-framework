<?php

namespace System\Exception;


class FilesystemException extends Exception
{

    function __construct($message, $code = 4, Exception $previous = null)
    {
        $msg = $message . ' does not seem to be available at this time.';
        parent::__construct($msg, $code, $previous);
    }
}