<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 21-03-15
 * Time: 01:16
 */

namespace System\Factories;


use System\Exception\BadMethodCallException;
use System\Exception\FilesystemException;
use System\Reflector;
use System\Traits\hasCollection;

class Factory
{

    /**
     * This class keeps a collection of classes that has been instantiated thru it.
     */
    use hasCollection;

    /**
     * Array containing which classes it knows about
     * @var array
     */
    public $available = array();

    /**
     * Array containing which classes it already holds an instance of
     * @var
     */
    public $LoadedClasses;

    /**
     * Options for class Factory, by default only a Loader needs to be defined, either Lazy or Eager are available.
     * @var array
     */
    protected static $options = array('Loader' => 'Lazy'); // Default.

    /**
     * Arguments for instantiating a class
     * @var array
     */
    protected $arguments = array();

    /**
     * Configuration for class in process
     * @var
     */
    protected $config;

    /**
     * Dependencies for current class in process
     * @var array
     */
    protected $dependencies = array();

    /**
     * Name of the class in process
     * @var
     */
    protected static $class;

    /**
     * Array containing dependencies defined in class, eg. a TypeHinted class in constructor.
     * @var array
     */
    private $deps = array();

    /**
     * Factory Constructor,
     * creates an index of files in path, recursively.
     * @param string $path
     */
    public function __construct($path = __DIR__)
    {
        $this->LoadedClasses = $this->findOrNew('Factory');
        $this->IndexAvailable($path, \FilesystemIterator::SKIP_DOTS);
    }

    /**
     * @param $class
     * @param array $options
     * @return static
     */
    public static function make($class, array $options = array())
    {
        if (!empty($options)) {
            static::$options = $options;
        }

        if (!empty($class)) {
            static::$class = $class;
            return new static;
        }

        throw new BadMethodCallException('undefined ' . $class . '@Factory::make()');
    }

    /**
     * @param $directory
     * @param $flags
     */
    private function IndexAvailable($directory, $flags)
    {
        foreach (new \RecursiveDirectoryIterator($directory, $flags) as $path => $cur) {
            $this->available[] = array(str_replace('/', '\\', str_replace('.php', '', strstr($path, 'Core') . DS . $cur->getFilename())));
        }
    }

    /**
     * @param array $arguments
     * @return $this
     */
    public function with(array $arguments = array())
    {
        $this->arguments[] = $arguments;
        return $this;
    }

    /**
     * @param $config
     * @return $this
     */
    public function Config($config)
    {
        $this->config = $config;
        return $this;
    }


    /**
     * A Single dependencies
     * @param callable $on
     * @return $this
     */
    public function depends(\Closure $on)
    {
        $this->dependencies[] = $on;
        return $this;
    }

    /**
     * An array of dependencies
     * @param array $dependencies
     * @return $this
     */
    public function MultipleDependencies(array $dependencies = array())
    {
        $this->dependencies[] = (array)$dependencies;
        return $this;
    }

    /**
     * Last method in chain
     * @return mixed
     * @throws FilesystemException
     */
    public function create()
    {
        $class = $this->AttemptLoad(static::$class);
        if (is_object($class)) {
            if (!empty($this->arguments))
                return $class->make($this->arguments);
            return $class->make();
        }
    }

    /**
     * Depending on $options, this will either call Lazy or Eager Loader.
     * @param $call
     * @return mixed
     */
    private function AttemptLoad($call)
    {
        return static::$options['Loader'] == 'Eager' ? $this->EagerLoader($call) : $this->LazyLoader($call);
    }

    /**
     * Call class and return.
     * @param $call
     * @return mixed
     */
    private function EagerLoader($call)
    {
        preg_match($call, $this->available, $class);
        return new $class();
    }

    /**
     * Call class, register the instance and return.
     * @param $call
     * @return mixed
     */
    private function LazyLoader($call)
    {
        return ($this->LoadedClasses->get($call)) ? $this->LoadedClasses->get($call) : $this->RegisterClassAndReturn($call);
    }

    /**
     * Part of LazyLoader
     * @param $call
     * @return mixed
     */
    private function RegisterClassAndReturn($call)
    {
        if (!empty($this->dependencies)) {
            $this->add($call, $this->ReturnOrCallClass($call, $this->ReturnOrCallForeign($this->dependencies)));
        } else {
            $this->add($call, $this->ReturnOrCallClass($call));
        }
        return $this->find($call);
    }

    /**
     * @param $class
     * @param string $dependencies
     * @return bool|object
     */
    private function ReturnOrCallClass($class, $dependencies = '')
    {
        $call = $this->findInArray($this->available, $class)[0][0];
        if (isset($call)) {
            $call = __NAMESPACE__ . $call . $call;
            if (!empty($dependencies)) {
                return Reflector::reflect($call, $dependencies);
            } elseif (!empty($this->config)) {
                return new $call($this->config);
            } else {
                return Reflector::reflect($call);
            }
        }
        $this->callForeign($class);
    }

    /**
     * Method for when a class isn't in registered path.
     * @param array $dependencies
     * @return string
     */
    private function ReturnOrCallForeign($dependencies = array())
    {
        foreach ($dependencies as $dependency) {
            $this->deps[] = is_object($dependency) ? $dependency : $this->callForeign($dependency);
        }
        return implode(', ', $this->deps);
    }

    /**
     * @param $object
     * @param $call
     * @return array
     */
    private function callForeign($object)
    {
        return in_array($object, $this->dependencies) ? $this->dependencies[$object] : debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
    }

    /**
     * Recursive needle search.
     * I could have done this with array_walk_recursive();
     * @param array $array
     * @param $object
     * @return array
     */
    private function findInArray(array $array = array(), $object)
    {
        $matches = array();
        foreach ($array as $v) {
            $split = explode('\\', $v[0]);
            for ($i = 0; $i < count($split); $i++) {
                if ($split[$i] == $object)
                    $matches = array($v);
            }
        }
        return $matches;
    }
}