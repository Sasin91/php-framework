<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 24-03-15
 * Time: 09:52
 */

namespace System\Factories\Cache\Adapters;


use System\Exception\FilesystemException;
use System\Interfaces\CacheInterface;

class File implements CacheInterface
{

    private static $path;
    private static $ROOT_PATH;
    private $data = array();

    public function __construct(array $info = array())
    {
        static::$ROOT_PATH = ROOT_PATH . DS . 'app' . DS . 'Storage' . DS . 'Cache' . DS;
        /**
         * $value = $info[0]['subDir'];
         * !empty($value) ? $target = $value : $target = null;
         * $dir = !empty($target) ? static::$ROOT_PATH.$target : static::$ROOT_PATH;
         * is_object($dir) ? static::$path = dir($dir)->path : static::$path = $dir; // changing static::$ROOT_PATH to $dir (intended), cause a chain of errors i don't see much logic in.
         **/
        static::$path = static::$ROOT_PATH;
        if (!is_dir(static::$path)) {
            mkdir(static::$path, 0777);
            chmod(static::$path, 0777, true);
        }

        return $this;
    }

    private function FindKeyInArray($key, array $array = array())
    {
        foreach ($array as $k => $v) {
            if($k == $key)
                return $v;
        }
    }

    /**
     * @param $key
     * @return bool|mixed|string, if array or string returns decoded json.
     */
    function get($key)
    {
        $encrypted_key = base64_encode(substr($key, 0, 11));
        $data = $this->FindKeyInArray($encrypted_key, $this->data);
        if (isset($data))
            if (time() >= $data['created'] + $data['lifetime'])
                return $key . ' expired';

        if(is_file(static::$path . $encrypted_key))
        {
            $file = file_get_contents(static::$path . $encrypted_key);
            $isJson = json_decode($file);
            return $file === false ? false : $isJson ? $isJson : $file;
        }
        return false;

    }

    /**
     * @param $key
     * @param $value
     * @param string $minutes
     */
    private $dir;

    function set($key, $value, $minutes = '60')
    {
        if (is_null($key))
            return null;

        if(is_array($value)) { $data = json_encode($value); } else { $data = $value; }
        $this->dir = dir(static::$path);
        $encrypted_key = base64_encode(substr($key, 0, 11));
        if (!is_null(file_put_contents($this->dir->path . $encrypted_key, $data))):
            $this->data[] =
                array(
                    'key' => $encrypted_key,
                    'value' => $data,
                    'lifetime' => $minutes,
                    'created' => time()
                );
            return true;
        else:
            throw new FilesystemException("Please check your filesystem permissions on " . static::$path . $encrypted_key . "");
        endif;
    }

    public function __toString()
    {
        return $this->dir;
    }

    public function delete($key)
    {
        unset($this->data[$key]);
        delete(static::$path . $key);
    }


    public function clear()
    {
        unset($this->data);
        $dir = static::$path;
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? rmdir("$dir$file") : unlink("$dir$file");
        }
        return rmdir($dir);
    }
}
