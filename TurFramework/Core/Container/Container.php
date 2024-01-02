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
     * @param  callable
     * @return void
     */
    public function bind($key, callable $callable)
    {
        $this->add($key, $callable);
    }

    /**
     * Resolve the value associated with the given key from the container.
     *
     * @param string $key
     * @return mixed
     * @throws ContainerException If the key does not exist in the container.
     */
    public function resolve($key)
    {
        if ($this->has($key)) {
            // throw new \InvalidArgumentException("Binding for '$key' not found in the container.");
            return call_user_func($this->bindings[$key]);
        }


        return $this->build($key);
    }

    public function build($key)
    {

        // 1. Inspect the class that we are trying to get from the container
        $reflectionClass = new \ReflectionClass($key);

        // 2. Inspect the constructor of the class
        $constructorClass = $reflectionClass->getConstructor();

        if (!$constructorClass) {
            return new $key();
        }

        // 3. Inspect the constructor parameters (dependencies)
        $parameters = $constructorClass->getParameters();

        if (!$parameters) {
            return new $key();
        }

        // 4. If the constructor parameter is a class then try to resolve that class using the container
        $dependencies = array_map(
            function (\ReflectionParameter $param) use ($key) {
                $name = $param->getName();
                $type = $param->getType();

                if (!$type) {
                    throw new ContainerException(
                        'Failed to resolve class "' . $key . '" because param "' . $name . '" is missing a type hint'
                    );
                }

                if ($type instanceof \ReflectionUnionType) {
                    throw new ContainerException(
                        'Failed to resolve class "' . $key . '" because of union type for param "' . $name . '"'
                    );
                }

                if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                    return $this->resolve($type->getName());
                }

                throw new ContainerException(
                    'Failed to resolve class "' . $key . '" because invalid param "' . $name . '"'
                );
            },
            $parameters
        );


        return $reflectionClass->newInstanceArgs($dependencies);
    }
    public function make($key)
    {
        if (!$this->has($key)) {
            throw new \InvalidArgumentException("Binding for '$key' not found in the container.");
        }

        return $this->bindings[$key];
    }
    /**
     * Check if a key exists within the container.
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->bindings);
    }

    /**
     * Get the bindings associated with the given key.
     *
     * @param string $key
     * @return mixed
     */
    public function getBindings()
    {
        return $this->bindings;
    }

    /**
     * Add a key-callable pair to the container bindings.
     *
     * @param string $key
     * @param mixed $callable
     * @return void
     */
    private function add($key, callable $callable)
    {
        $this->bindings[$key] = $callable;
    }
}
