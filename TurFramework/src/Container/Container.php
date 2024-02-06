<?php

namespace TurFramework\Container;

use Closure;
use Reflection;
use TurFramework\Container\ContainerException;

class Container
{

    /**
     * The container's bindings.
     *
     * @var array[]
     */
    protected $bindings = [];

    /**
     * The singleton instance of the Container.
     *
     * @var self|null
     */
    protected static $instance;

    /**
     * Get the globally available instance of the container.
     *
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Set the shared instance of the container.
     *
     * @param  Container|null  $container
     * @return Container|static
     */
    public static function setInstance(Container $container = null)
    {
        return static::$instance = $container;
    }

    /**
     * Register a binding with the container.
     *
     * @param  string  $abstract
     * @param  \Closure|string|null  $concrete
     * @return void
     *
     * @throws \TypeError
     */
    public function bind($abstract, $concrete = null)
    {

        // If no concrete type was given, 
        // we will simply set the concrete type to the abstract type.
        if (is_null($concrete)) {
            $concrete = $abstract;
        }
        // If the factory is not a Closure or string, throw TypeError
        if (!$concrete instanceof Closure && !is_string($concrete)) {
            throw new \TypeError(self::class . '::bind(): Argument #2 ($concrete) must be of type Closure|string|null');
        }


        $this->bindings[$abstract] = $this->build($concrete);
    }
    /**
     * Register a singleton binding with the container.
     *
     * @param  string  $abstract
     * @param  \Closure|string|null  $concrete
     * @return void
     *
     * @throws \TypeError
     */
    public function singleton($abstract, $concrete = null)
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        if (!$concrete instanceof Closure && !is_string($concrete)) {
            throw new \TypeError(self::class . '::singleton(): Argument #2 ($concrete) must be of type Closure|string|null');
        }

        $this->bindings[$abstract] = $this->share(function ($container) use ($concrete) {
            return $container->build($concrete);
        });
    }

    /**
     * Share the Closure across the application.
     *
     * @param  \Closure  $closure
     * @return \Closure
     */
    public function share(Closure $closure)
    {
        return function ($container) use ($closure) {
            static $instance;

            if (is_null($instance)) {
                $instance = $closure($container);
            }

            return $instance;
        };
    }

    /**
     * Resolve the given type from the container.
     *
     * @param  string|callable  $abstract
     * @param  array  $parameters
     * @return mixed
     *
     */
    public function make($abstract)
    {
        return $this->resolve($abstract);
    }


    /**
     * Get the concrete type for a given abstract.
     *
     * @param  string|callable  $abstract
     * @return mixed
     */
    protected function getConcrete($abstract)
    {
        return $this->bindings[$abstract] ?? $abstract;
    }

    /**
     * Resolve the value associated with the given key from the container.
     *
     * @param string $abstract
     * @return mixed
     */
    protected function resolve($abstract)
    {

        // 1- Get the concrete type for a given abstract.
        $concrete = $this->getConcrete($abstract);

        if ($this->has($abstract)) {
            return $concrete;
        }
        return $this->build($concrete);
    }


    protected function build($concrete)
    {
        // If the concrete type is actually a Closure, we will just execute it and
        // hand back the results of the functions, which allows functions to be
        // used as resolvers for more fine-tuned resolution of these objects.
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }


        // 1. Inspect the class that we are trying to get from the container
        $reflectionClass = new \ReflectionClass($concrete);

        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException('Class "' . $concrete . '" is not instantiable');
        }
        // 2. Inspect the constructor of the class
        $constructorClass = $reflectionClass->getConstructor();

        if (!$constructorClass) {
            return new $concrete($this);
        }

        // 3. Inspect the constructor parameters (dependencies)
        $parameters = $constructorClass->getParameters();

        if (!$parameters) {
            return new $concrete($this);
        }

        // 4. If the constructor parameter is a class then try to resolve that class using the container
        $dependencies = $this->resolveDependencies($parameters, $concrete);

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    /**
     * Resolve all of the dependencies from the ReflectionParameters.
     *
     * @param  \ReflectionParameter[]  $dependencies
     * @return array
    
     */
    private function resolveDependencies(array $dependencies, $abstract)
    {

        $results = [];

        foreach ($dependencies as $dependency) {
            $name = $dependency->getName();
            $type = $dependency->getType();

            if (!$type) {
                throw ContainerException::missingTypeHint($abstract, $name);
            }
            if ($type instanceof \ReflectionUnionType) {
                throw  ContainerException::unionType($abstract, $name);
            }

            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                $results[] = $this->resolve($type->getName());
            }
            throw  ContainerException::invalidParam($abstract, $name);
            break;
        }

        return $results;
    }
    /**
     * Check if a abstract exists within the container.
     *
     * @param string $abstract
     * @return bool
     */
    public function has($abstract)
    {
        return array_key_exists($abstract, $this->bindings);
    }

    /**
     * Get the bindings associated with the given key.
     *
     * @param string $abstract
     * @return mixed
     */
    public function getBindings()
    {
        return $this->bindings;
    }
}
