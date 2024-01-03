<?php

namespace TurFramework\Core\Container;

use Reflection;
use TurFramework\Core\Container\ContainerException;

class Container
{
    /**
     * The array of registered bindings.
     *
     * @var array
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
     * Register a binding with the container.
     *
     * @param  string  $abstract
     * @param  \Closure|string|null  $concrete
     * @return void
     */
    public function bind($abstract, $concrete)
    {
        $this->add($abstract, $concrete);
    }

    /**
     * Resolve the value associated with the given key from the container.
     *
     * @param string $abstract
     * @return mixed
     * @throws ContainerException If the key does not exist in the container.
     */
    public function resolve($abstract)
    {
        if ($this->has($abstract)) {
            // throw new \InvalidArgumentException("Binding for '$abstract' not found in the container.");
            return call_user_func($this->bindings[$abstract]);
        }


        return $this->build($abstract);
    }

    public function build($abstract)
    {

        // 1. Inspect the class that we are trying to get from the container
        $reflectionClass = new \ReflectionClass($abstract);

        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException('Class "' . $abstract . '" is not instantiable');
        }
        // 2. Inspect the constructor of the class
        $constructorClass = $reflectionClass->getConstructor();

        if (!$constructorClass) {
            return new $abstract();
        }

        // 3. Inspect the constructor parameters (dependencies)
        $parameters = $constructorClass->getParameters();

        if (!$parameters) {
            return new $abstract();
        }

        // 4. If the constructor parameter is a class then try to resolve that class using the container
        $dependencies = $this->resolveDependencies($parameters, $abstract);

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
                throw new ContainerException(
                    'Failed to resolve class "' . $abstract . '" because param "' . $name . '" is missing a type hint'
                );
            }
            if ($type instanceof \ReflectionUnionType) {
                throw new ContainerException(
                    'Failed to resolve class "' . $abstract . '" because of union type for param "' . $name . '"'
                );
            }
            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                $results[] = $this->resolve($type->getName());
                break;
            }
            throw new ContainerException(
                'Failed to resolve class "' . $abstract . '" because invalid param "' . $name . '"'
            );
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

    /**
     * Add a key-callable pair to the container bindings.
     *
     * @param string $abstract
     * @param \Closure|string|null  $concrete
     * @return void
     */
    private function add($abstract, $concrete)
    {
        $this->bindings[$abstract] = $concrete;
    }
}
