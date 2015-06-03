<?php
/**
 * Created by PhpStorm.
 * User: JonasK
 * Date: 09-01-2015
 * Time: 23:02
 */

namespace System\Secrets;


use Config;

class Token
{

    protected $faker;
    protected $cache = array();
    protected static $_instance;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create(Config::get('Config')['Base']['language']);
        static::$_instance = $this;
    }

    /**
     * Generates an encrypted string based on length of input.
     * @param $string string
     * @return string
     */
    private $encryptedValue = '';

    public function encrypt($string = '')
    {
        $length = !empty($string) ? strlen($string) : '10';
        if (extension_loaded('openssl')) {
            $hex = bin2hex(openssl_random_pseudo_bytes($length));
            $this->encryptedValue = base64_encode($hex);
        } elseif (PHP_OS == 'Linux' || PHP_OS == 'FreeBSD' || PHP_OS == 'OpenBSD') {
            $this->encryptedValue = base64_encode($this->urandom(72) . uniqid(true) . mt_rand());
        } else {
            $this->encryptedValue = base64_encode(md5(uniqid(true) . microtime() . rand()));
        }

        $this->cache[$string] = $this->encryptedValue;
        return $this->encryptedValue;
    }

    public function decrypt($string)
    {
        $token = $this->cache[$string];
        if (!empty($token)) {
            if (base64_decode($token)) {
                return true;
            }
        }
        return false;
    }

    private function urandom($len)
    {
        $fp = @fopen('/dev/urandom', 'rb');
        $result = '';
        if ($fp !== FALSE) {
            $result .= @fread($fp, $len);
            @fclose($fp);
        } else {
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