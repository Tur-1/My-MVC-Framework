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
        $name = $name ?: $this->getDefaultGuard();

        if (!isset($this->guards[$name])) {
            $this->guards[$name] = $this->resolve($name);
        }

        return $this->guards[$name];
    }

    public function resolve($name)
    {
        $config = $this->getConfigGuard($name);

        $authProvider = new AuthProvider($config);

        return new AuthenticationGuard(
            $name,
            $this->app->make('session'),
            $authProvider
        );
    }

    /**
     * Dynamically call the default Guard instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->guard()->{$method}(...$parameters);
    }
    protected function getConfigGuard($name)
    {
        return config('auth.guards.' . $name);
    }

    /**
     * Get the default authentication Guard name.
     *
     * @return string
     */
    public function getDefaultGuard()
    {
        return config('auth.default.guard');
    }
}
