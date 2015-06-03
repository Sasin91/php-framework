<?php

namespace System;

use System\MVC\Core;
use Toolbox\StringTools;

/**
 * Framework Kernel,
 * A core and foundation component of the framework.
 * Class Kernel
 * @package System
 */
class Kernel
{
    /**
     * A lightweight array of classes including Fully qualified namespaces.
     * @var array
     */
    public $classes = array();

    /**
     * Messages received or send
     * @var array
     */
    public $messages = array();

    /**
     * Instance of \AutoLoader
     * @var
     */
    public $AutoLoader;

    /**
     * Reflector instance, albeit not utilized in Kernel, there is many classes which extends from this and will utilize it.
     * @var
     */
    protected $reflector;

    /**
     * Framework Config (app/Config/Config.yml)
     * @var array
     */
    protected $config;

    /**
     * Instance of MessageBus
     * @var
     */
    protected $messageBus;

    /**
     * Instance of Dispatch
     * @var
     */
    protected $dispatcher;

    /**
     * Array containing paths to files defined in Autoload block @config.yml
     * @var array
     */
    private $paths = array();

    /**
     * Paths to scan for files, to attempt intelligent load
     * @var array
     */
    private $scanPaths = array();

    public function __construct(array $config, \Autoloader $AutoLoader)
    {
        /**
         * Set Configurations
         */
        $this->config = $config;

        /**
         * Register the injected AutoLoader.
         */
        $this->AutoLoader = $AutoLoader;

        foreach ($this->config['ScanPaths'] as $path) {
            $this->scanPaths = $this->indexPathTo($path, \FilesystemIterator::SKIP_DOTS);
        }



        /**
         * Iterate (or loop) thru the namespaces in Autoload section @Config
         */
        foreach ($this->config['Autoload'] as $namespace) {
            /**
             * Return last part of a string, by using a Delimiter.
             */
            $filename = StringTools::getStringBy('\\', $namespace)->Last();

            /**
             * Instantiate the LazyLoader.
             */
            LazyLoader::_init();

            if ($filename === '*') {
                /**
                 * Index the path to the given file.
                 */
                $this->paths = $this->indexPathTo($namespace, \FilesystemIterator::SKIP_DOTS);

                /**
                 * Iterate thru the paths.
                 */
                foreach ($this->paths as $file => $path) {
                    /**
                     * Remove .php extension from file and register.
                     */
                    $name = str_replace('.php', '', $file);
                    $this->classes[$name] = Reflector::reflect($path[0]);
                } // end Foreach($this->paths..

            } else {
                $this->classes[$filename] = Reflector::reflect($namespace);
            }
        } // end Foreach($this->config['Autoload']
        foreach ($this->classes as $name => $class) {
            /**
             * Register the name and class with LazyLoader.
             */
            LazyLoader::add($name, $class);
        }
        /**
         * Push the whole array of registered paths to spl_autoloader
         */
        LazyLoader::register();

        /**
         * Instantiate our MVC Core.
         */
        Core::_init(new \app($this), $this->config);

        /**
         * Instantiate the Dispatcher
         */
        $this->dispatcher = new Dispatch($this->classes);

        /**
         * Instantiate the messaging bus class
         */
        $this->messageBus = new MessageBus($this->dispatcher);
    }

    /**
     * @param $namespace
     * @param $flags
     */
    private function indexPathTo($namespace, $flags)
    {
        /**
         * Create an Array from the canonical path to Kernel.,
         * then remove first and last element.
         */
        $directory = StringTools::getStringBy('/', __DIR__)->Entire()->asArray();
        array_pop($directory); // There is most likely a much cleaner way of doing this.
        array_shift($directory);

        /**
         * Create an Array using given namespace,
         * then remove last element from array.
         */
        $path = StringTools::getStringBy('\\', $namespace)->Entire()->asArray();
        array_pop($path);

        /**
         * Merge the two Arrays, then add a slash to start of Array
         */
        $directory = array_merge_recursive($directory, $path);
        array_unshift($directory, '/');

        /**
         * Iterate thru the merged arrays.
         */
        $paths = array();
        foreach (new \RecursiveDirectoryIterator(implode('/', $directory), $flags) as $path => $cur) {
            $paths[str_replace('.php', '', $cur->getFilename())] = array(
                str_replace('app\\', '',
                    str_replace('/', '\\',
                        str_replace('.php', '',
                            strstr($path, 'app')
                        )
                    )
                )
            );
        }
        return $paths;
    }

    /**
     * @return messageBus
     */
    public function message()
    {
        return $this->messageBus;
    }

    /**
     * @param $receiver
     * @param string $method
     * @param $message
     * @return bool
     */
    public function dispatch($receiver, $sender, $method = '',  $message)
    {
        return $this->dispatcher->fire($receiver, $sender, $method, $message);
    }

    public function load($class)
    {
        $lazy = $this->tryLazyLoader($class);
        if($lazy) return $lazy;

        foreach ($this->scanPaths as $name => $path) {
            if (class_exists($path[0] . '\\' . $class)) {
                $this->classes[$class] = Reflector::reflect($path[0] . '\\' . $class);
                return $this->classes[$class];
            }
        }

        return $this->AutoLoader->load($class);
    }

    public function getConfig()
    {
        return $this->config;
    }


    protected function tryLazyLoader($class)
    {
        return LazyLoader::get($class);
    }
}
