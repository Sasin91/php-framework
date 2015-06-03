<?php

use System\Traits\ArrayAccess;
use System\Traits\hasCollection;

class Config implements \ArrayAccess
{
    use ArrayAccess, hasCollection;
    public static $config;
    public static $availableKeys = array();

    protected $yaml;
    protected $dumper;


    protected function getYaml()
    {
        if (!is_object($this->yaml)) {
            $this->yaml = new \Symfony\Component\Yaml\Parser();
        }

        if (!is_object($this->dumper)) {
            $this->dumper = new \Symfony\Component\Yaml\Dumper();
        }
    }


    public static function getInstance()
    {
        return new static;
    }

    /**
     * Retrieve a configuration by name ( key )
     * @param $name
     * @return array|bool
     */
    public static function get($name)
    {
        $instance = new static;
        return $instance->exist($name, true);
    }

    /**
     * Update a configuration
     * @param $name
     * @param array $content
     * @return mixed
     */
    public static function update($name, array $content = array(), $toFile = false)
    {
        $instance = new static;

        $file = $instance->exist($name, true);
        return $toFile ? $instance->write($name, array_replace($file, $content)) : $instance->softWrite($name, array_replace($file, $content));
    }

    /**
     * Remove a configuration
     * @param $name
     * @return bool
     */
    public static function remove($name)
    {
        $instance = new static;
        if ($instance->exist($name)) {
            unset(static::$config[$name]);
            unlink(__DIR__ . 'Config/' . $name);
        }
        return true;
    }


    /**
     * Only writes the configuration file in software
     * @param $name
     * @param array $content
     */
    public static function softWrite($name, $content)
    {
        $instance = new static;
        if ($instance->exist($name)) {
            $original = $instance->offsetExist($name, true);
            static::$config->attributes = array_replace($content, $original);
        } else {
            static::$config->attributes[$name] = $content;
        }
        return true;
    }


    /**
     * Roll back a SoftWritten config
     */
    public static function rollback()
    {
        self::$config = array();
    }


    /**
     * @param Config $instance
     * @param $name
     * @return bool|array
     */
    public function exist($name, $returnFile = false)
    {
        $this->getYaml();

        $this->indexIfConfigIsEmpty();

        $request = array();

        $array = $this->offsetExist($name, true);

        if ($array === false) {
            return false;
        }

        if (is_array($array)) {
            if (in_array($name, array_keys($array))) {
                $request = $array[$name];
            }

            if ($returnFile) {
                if (empty($request)) {
                    if (in_array($name, array_keys($array))) {
                        $file = $array[$name];
                    }
                    $request = !empty($file) ? $file : false;
                }

                if ($request === false) {
                    $request = $this->tryWithKey($name);
                }
                if (!is_array($request))
                    return $this->convertYamlToArray($request);
                return $request;

            }
            return !empty($request) ? true : false;
        }
        return false;
    }

    public function tryWithKey($key)
    {
        $keys = $this->getAttributes();
        foreach ($keys as $k => $v) {
            if (!is_null($key)) {
                if ($k == $key)
                    return $v;
            }
            return false;
        }
    }

    /**
     * Convert an Array to a Yaml
     * @param array $array
     */
    public function convertArrayToYaml(array $array)
    {
        if (!is_object(($this->dumper))) {
            $this->getYaml();
        }

        return $this->dumper->dump($array);
    }

    public function convertYamlToArray($file)
    {
        if (!is_object(($this->yaml))) {
            $this->getYaml();
        }
        return $this->yaml->parse($file);

    }


    /**
     * write a new configuration file
     * @param $name
     * @param $content
     * @return bool|int
     */
    public function write($name, $content)
    {
        self::softWrite($name, $content);

        if (is_array($content)) {
            $content = $this->convertArrayToYaml($content);
        }
        return file_put_contents(BASE_PATH . DS . 'app/Config/' . $name . '.yml', $content) != false ? true : false;
    }

    /**
     * @param $instance
     */
    private function indexIfConfigIsEmpty()
    {
        $attr = $this->getAttributes();;
        if (empty($attr)) {
            $this->indexAvailable();
        }
    }

    /**
     * Create and store all available configurations
     */
    private function indexAvailable()
    {
        $configurations = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(realpath(__DIR__ . DS . '/Config')));
        $config = array();
        foreach ($configurations as $name => $object) {
            $last = strstr(array_pop(explode('/', $name)), '.', true);
            if (strlen($last) > 2) {
                $config[$last] = $this->convertYamlToArray(file_get_contents($object->getPathname()));
            }
        }
        static::$config = $this->newCollection('Config', $config);
    }

    protected function offsetExist($offset, $returnFile = false)
    {
        $request = $this->getAttributes()[$offset];
        $exist = !empty($request);
        if ($exist) {
            if ($returnFile)
                return $request;
            return $exist;
        }
        return false;
    }

    public function getAttributes()
    {
        if (isset(static::$config->attributes)) {
            $attr = static::$config->attributes;

        } else {

            $attr = array();

        }

        return !is_array($attr) ? $this->convertYamlToArray($attr) : $attr;
    }
}