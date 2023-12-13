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
     * Bind a key to a value within the container.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function bind($key, $value)
    {
        $this->add($key, $value);
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

        return call_user_func($this->bindings[$key], $this);
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
    private function getBindings($key)
    {
        return $this->bindings[$key];
    }

    /**
     * Add a key-value pair to the container bindings.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    private function add($key, $value)
    {
        $this->bindings[$key] = $value;
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
