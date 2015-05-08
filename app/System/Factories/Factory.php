<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 21-03-15
 * Time: 01:16
 */

namespace System\Factories;


use System\Exception\FilesystemException;
use  System\Containers\ObjectContainer;
use System\Reflector;

class Factory {

    protected $available = array();
    protected $LoadedClasses = array();
    protected $options = array('Loader' => 'Lazy'); // Default.
    protected $arguments = array();
    protected $dependencies = array();
    protected $class;

    public function __construct($path = __DIR__)
    {
        $this->IndexAvailable($path,\FilesystemIterator::SKIP_DOTS);
    }

    public static function make($class, array $options = array())
    {
        $instance = new static;
        if(!empty($options)) {
            $instance->options = $options;
        }
        $instance->class = $class;
        return $instance;
    }

    private function IndexAvailable($directory, $flags)
    {
        foreach (new \RecursiveDirectoryIterator($directory, $flags) as $path=>$cur) {
            $this->available[] = array(str_replace('/', '\\',str_replace('.php', '', strstr($path, 'Core').DS.$cur->getFilename())));
        }
    }

    /**
     * @param $call
     * @return mixed
     */
    private function LazyLoader($call)
    {
        $this->LoadedClasses = ObjectContainer::create('Factory');
        return $this->LoadedClasses->get($call) ? $this->LoadedClasses->get($call) : $this->RegisterClassAndReturn($call);
    }

    private function RegisterClassAndReturn($call)
    {
            if(!empty($this->dependencies))
            {
                $this->LoadedClasses->set($call, $this->ReturnOrCallClass($call, $this->ReturnOrCallForeign($this->dependencies)));
            } else {
                $this->LoadedClasses->set($call, $this->ReturnOrCallClass($call));
            }
            return $this->LoadedClasses->get($call);
    }

    private $deps = array();
    private function ReturnOrCallForeign($dependencies = array())
    {
        foreach ($dependencies as $dependency) {
            $this->deps[] = is_object($dependency) ? $dependency : $this->callForeign($dependency);
        }
        return implode(', ', $this->deps);
    }

    private function ReturnOrCallClass($class, $dependencies = '')
    {
        $call = $this->findInArray($this->available, $class)[0][0];
        if($call)
            if(isset($dependencies)) {
                return Reflector::reflect($call, $dependencies);
            } else {
                return Reflector::reflect($call);
            }
        $this->callForeign($class);
    }

    private function findInArray(array $array = array(), $object)
    {
        $matches = array();
        foreach ($array as $v) {
             $split = explode('\\', $v[0]);
            for($i = 0; $i < count($split); $i++)
            {
                if($split[$i] == $object)
                    $matches = array($v);
            }
        }
        return $matches;
    }

    private function EagerLoader($call)
    {
        preg_match($call, $this->available, $class);
        return new $class();
    }
    
    private function AttemptLoad($call)
    {
        return $this->options['Loader'] == 'Eager' ? $this->EagerLoader($call) : $this->LazyLoader($call);
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
     * A Single dependencies
     * @param callable $on
     * @return $this
     */
    public function depends(\Closure $on)
    {
        $this->dependencies[] = $on;
        return $this;
    }

    public function MultipleDependencies(array $dependencies = array())
    {
        $this->dependencies[] = (array)$dependencies;
        return $this;
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

    public function create()
    {
        $class = $this->AttemptLoad($this->class);
        if(is_object($class)) {
            if (!empty($this->arguments))
                return $class->make($this->arguments);
            return $class->make();
        }
        throw new FilesystemException('Factory cannot reach: '.$this->class);
    }
}