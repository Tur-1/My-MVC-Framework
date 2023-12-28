<?php

namespace TurFramework\Core\Container;

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
        if (!$this->has($key)) {
            throw new \InvalidArgumentException("Binding for '$key' not found in the container.");
        }


        return call_user_func($this->bindings[$key]);
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
