<?php
/**
 * Created by PhpStorm.
 * User: xqq-exponent
 * file: Container.php
 * Date: 2018/3/1
 * Time: 15:49
 */

namespace frame;


class Container
{
    private $binds = [];

    private static $instance = null;

    private function __construct(){}

    private function __clone(){}

    public static function getInstance()
    {
        if(!self::$instance instanceof self){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function bind($abstract, $concrete = null, $shared = false)
    {
        if(is_null($concrete)){
            $concrete = $abstract;
        }

        if(!$concrete instanceof \Closure){
            $concrete = $this->getClosure($abstract, $concrete);
        }
        $this->binds[$abstract] = compact("concrete", "shared");
    }

    public function singleton($abstract, $concrete, $shared = true)
    {
        $this->bind($abstract, $concrete, $shared);
    }

    /**
     * @param $abstract
     * @return mixed
     * @throws \Exception
     */
    public function make($abstract)
    {
        $concrete = $this->getConcrete($abstract);

        if($this->isBuildable($concrete, $abstract)){
            $object = $this->build($concrete);
        }else{
            $object = $this->make($concrete);
        }
        return $object;
    }

    public function getClosure($abstract, $concrete)
    {
        return function (Container $container) use ($abstract, $concrete){
            $method = ($abstract == $concrete) ? 'build' : 'make';
            return $container->$method($concrete);
        };
    }

    public function getConcrete($abstract)
    {
        if(!isset($this->binds[$abstract])){
            return $abstract;
        }
        return $this->binds[$abstract]['concrete'];
    }

    /**
     * @param $concrete
     * @return mixed
     * @throws \Exception
     */
    public function build($concrete)
    {
        if($concrete instanceof \Closure){
            return $concrete($this);
        }

        $reflector = new \ReflectionClass($concrete);
        if(!$reflector->isInstantiable()){
            throw new \Exception('无法实例化！');
        }
        $constructor = $reflector->getConstructor();
        if(is_null($constructor)){
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();
        $instance = $this->getDependencies($dependencies);
        return $reflector->newInstanceArgs($instance);
    }

    public function isBuildable($concrete, $abstract)
    {
        return $concrete == $abstract || $concrete instanceof \Closure;
    }

    /**
     * @param array $dependencies
     * @return array
     * @throws \Exception
     */
    public function getDependencies(array $dependencies)
    {
        $result = [];
        /**@var \ReflectionParameter $dependency*/
        foreach ($dependencies as $dependency){
            if(is_null($dependency->getClass())){
                $result[] = $this->resolvedNonClass($dependency);
            }else{
                $result[] = $this->resolvedClass($dependency);
            }
        }
        return $result;
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return mixed
     * @throws \Exception
     */
    public function resolvedNonClass(\ReflectionParameter $parameter)
    {
        if($parameter->isDefaultValueAvailable()){
            return $parameter->getDefaultValue();
        }
        throw new \Exception('出错');
    }

    public function resolvedClass(\ReflectionParameter $parameter)
    {
        return $this->make($parameter->getClass()->name);
    }
}