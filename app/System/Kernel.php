<?php

namespace System;
use Toolbox\StringTools;


class Kernel
{
    protected $reflector;
    public $classes = array();
    protected $config;
    public function __construct(array $config)
    {
        $this->config = $config;
        if($this->config['Composer'] === true)
        {
            require_once(ROOT_PATH . DS . 'Libraries/vendor/autoload.php');
        }
        foreach($this->config['Autoload'] as $namespace)
        {
            $filename = StringTools::getStringBy('\\', $namespace)->Last();
            LazyLoader::_init();
                if($filename === '*')
                {
                    $this->indexPathTo($namespace, \FilesystemIterator::SKIP_DOTS);
                    foreach ($this->paths as $file => $path) {
                        $name = str_replace('.php', '', $file);
                            $this->classes[$name] = Reflector::reflect($path[0]);
                    }
                } else {
            $this->classes[$filename] = Reflector::reflect($namespace);
            }
        }
        foreach ($this->classes as $name => $class) {
            LazyLoader::set($name, $class);
        }
        LazyLoader::register();
    }


    private $paths = array();
    private function indexPathTo($namespace, $flags)
    {
        $directory = StringTools::getStringBy('/', __DIR__)->Entire()->asArray();
        array_pop($directory); // There is most likely a much cleaner way of doing this.
        array_pop($directory);
        array_shift($directory);

        $path = StringTools::getStringBy('\\', $namespace)->Entire()->asArray();
        array_pop($path);
        $directory = array_merge_recursive($directory, $path);
        array_unshift($directory, '/');
        foreach (new \RecursiveDirectoryIterator(implode('/', $directory), $flags) as $path=>$cur) {
            $this->paths[str_replace('.php','',$cur->getFilename())] = array(str_replace('app\\', '', str_replace('/', '\\',str_replace('.php', '', strstr($path, 'app')))));
        }
    }

}
