<?php

use System\Traits\ArrayAccess;

class Config implements \ArrayAccess
{
    use ArrayAccess;
    public $config = array();

        public static function get($name)
		{
            $instance = new static;
                if(empty($instance->config))
                {
                    $instance->indexAvailable();
                }
            return $instance->config[$name];
		}

        public static function set($name, array $content = array())
        {
            $instance = new static;
            if($instance->exist($name))
            {
               return false;
            } else {
               return file_put_contents($name, $content);
            }
        }

        public static function update($name, array $content = array())
        {
            $instance = new static;
            $file = $instance->get($name);
            $instance->remove($name);
            return $instance->set($name, $file + $content);
        }

        public static function remove($name)
        {
            $instance = new static;
            unset($instance->config[$name]);
            unlink($name);
        }

        private function indexAvailable()
        {
            $configurations = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(realpath(__DIR__.DS.'/Config')));
            foreach ($configurations as $name => $object) {
                $last = strstr(array_pop(explode('/', $name)), '.', true);
                if (strlen($last) > 2) {
                    $this->config[$last] = require($object->getPathname());
                }
            }
        }

        private function exist($name)
        {
            return in_array($name, $this->config);
        }
}