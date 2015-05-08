<?php
/**
 * Created by PhpStorm.
 * User: JonasK
 * Date: 09-01-2015
 * Time: 23:02
 */

namespace System\Secrets;


use System\Factories\Factory;
use Config\Config;

class Token {

    protected $faker;
    protected $cache;
    function __construct()
    {
        $this->faker = \Faker\Factory::create(Config::get('System/Config')['Base']['language']);
        $this->cache = Factory::make('Cache')->create('File');
    }

    /**
     * Generates an encrypted string based on length of input.
     * @param $string string
     * @return string
     */
    private static $encryptedValue = '';
    public static function encrypt($string = '')
    {
        $instance = new static;
        $length = !empty($string) ? strlen($string) : '10';
        if(extension_loaded('openssl'))
        {
            $hex = bin2hex(openssl_random_pseudo_bytes($length));
            static::$encryptedValue = base64_encode($hex);
        }
        elseif(PHP_OS == 'Linux' || PHP_OS == 'FreeBSD' || PHP_OS == 'OpenBSD')
        {
            static::$encryptedValue = base64_encode($instance->urandom(72) . uniqid(true) . mt_rand());
        }
        else
        {
            static::$encryptedValue = base64_encode(md5(uniqid(true) . microtime() . rand()));
        }
        if(!is_object($instance->cache)) $instance->__construct();
        $instance->cache->set($string, static::$encryptedValue);
        return static::$encryptedValue;
    }

    public static function decrypt($string)
    {
        $instance = new static;
        if(!is_object($instance->cache)) $instance->__construct();
        $token = $instance->cache->get($string);
        if(!empty($token))
        {
            if(base64_decode($token))
            {
                return true;
            }
        }
        return false;
    }

   private static function urandom($len)
    {
        $fp = @fopen('/dev/urandom','rb');
        $result = '';
        if ($fp !== FALSE) {
            $result .= @fread($fp, $len);
            @fclose($fp);
        }
        else
        {
            trigger_error('Can not open /dev/urandom.');
        }
        // convert from binary to string
        $result = base64_encode($result);
        // remove none url chars
        $result = strtr($result, '+/', '-_');
        // Remove = from the end
        $result = str_replace('=', ' ', $result);
        return $result;
    }
}