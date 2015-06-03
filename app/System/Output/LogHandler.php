<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 21-05-15
 * Time: 21:58
 */

namespace System\Output;


class LogHandler {

    public static function newLog($name)
    {
        file_put_contents( BASE_PATH . DS . 'app/Storage/Logs' . DS . $name . '.log', 'created at '.date('m-d-Y') . PHP_EOL);
    }

    public static function writeInto($log, $content)
    {
        file_put_contents(BASE_PATH . DS . 'app/Storage/Logs' . DS . $log . '.log', $content, FILE_APPEND);
    }

    public static function writeOrNew($log, $content)
    {
        $instance = new static;

        if(!file_exists(BASE_PATH . DS . 'app/Storage/Logs' . DS . $log . '.log'))
        {
            $instance->newLog($log);
        }

        $instance->writeInto($log, $content);
    }

    public static function get($log, $returnFilePath = false)
    {
        if(file_exists(BASE_PATH . DS . 'app/Storage/Logs' . DS . $log . '.log'))
        {
            if ($returnFilePath)
                return (BASE_PATH . DS . 'app/Storage/Logs' . DS . $log . '.log');
            return file_get_contents(BASE_PATH . DS . 'app/Storage/Logs' . DS . $log . '.log');
        }
        return false;
    }

    public static function getOrNew($log, $returnFilePath = false)
    {
        $instance = new static;
        if(!$instance->get($log))
        {
            $instance->newLog($log);
        }
        return $instance->get($log, $returnFilePath);
    }

    public static function writeAccessLog()
    {
        $instance = new static;
           $raw = array(
                $_SERVER,
                $_REQUEST
           );
          $instance->writeOrNew('access', $instance->formatAccessLog($raw[0]));
    }

    private function formatAccessLog($raw)
    {
        return '[' . $raw['REMOTE_ADDR'] .' { '. $raw['HTTP_USER_AGENT']. '] } '.$raw['REQUEST_METHOD'].' [' . $raw['HTTP_HOST'].$raw['REQUEST_URI'] . ']' . "\r\n";
    }

}