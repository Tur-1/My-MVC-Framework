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
    public $bindings = [];

    /**
     * The singleton instance of the Container.
     *
     * @var self|null
     */
    protected static $instance;

    /**
     * Get the singleton instance of the Container.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new static;
        }

        return self::$instance;
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


    /**
     * Register core aliases and their dependencies into the container.
     *
     * @param array $aliases
     * @return void
     */
    public function registerCoreAliases(array $aliases): void
    {
        // 

        foreach ($aliases as $key => $alias) {
            if ($this->hasDependencies($alias)) {
                $this->bind($key, function () use ($alias) {
                    return new $alias['class'](...$this->resolveDependencies($alias));
                });
            } else {
                $this->bind($key, function () use ($alias) {
                    return new $alias();
                });
            }
        }
    }
    private function hasDependencies($alias)
    {
        return is_array($alias) && $alias['dependencies'];
    }
    private function resolveDependencies($alias)
    {
        $dependencies = [];
        foreach ($alias['dependencies'] as $dependency) {

            $dependencies[] = $this->resolve($dependency);
        }

        return $dependencies;
    }
}
