<?php

namespace TurFramework\Auth;

use TurFramework\Auth\AuthProvider;

class AuthManager
{

    public $guards = [];
    public $app;

    public function __construct($app)
    {
        $this->app = $app;
    }
    /**
     * Attempt to get the guard from the local cache.
     *
     * @param  string|null  $name 
     */
    public function guard($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        if (!isset($this->guards[$name])) {
            $this->guards[$name] = $this->resolve($name);
        }

        return $this->guards[$name];
    }

    public function resolve($name)
    {
        $config = $this->getConfig($name);

        $authProvider = new AuthProvider($config);

        $garud = new Authentication($name, $this->app->make('session'), $authProvider);

        return $garud;
    }
    private function storeGaurd()
    {
    }
    /**
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->guard()->{$method}(...$parameters);
    }
    protected function getConfig($name)
    {
        return config('auth.guards.' . $name);
    }

    /**
     * Get the default authentication driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return config('auth.defaults.guard');
    }
}
