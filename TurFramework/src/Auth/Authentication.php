<?php

namespace TurFramework\Auth;

use TurFramework\Auth\UserProvider;
use TurFramework\Session\Store;

class Authentication
{

    protected $session;
    protected $userProvider;
    protected $guards = [];

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @param  bool  $remember
     * @return bool
     */
    public function attempt(array $credentials = [])
    {
        $this->userProvider = new UserProvider($this->getGuard());

        $user = $this->userProvider->retrieveByCredentials($credentials);


        if ($this->hasValidCredentials($user, $credentials)) {
            $this->login($user);

            return true;
        }

        return false;
    }
    /**
     * Determine if the user matches the credentials.
     *
     * @param  mixed  $user
     * @param  array  $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return $this->userProvider->validateCredentials($user, $credentials);
    }
    public function login()
    {
    }
    public function setUser()
    {
    }
    /**
     * Get a unique identifier for the auth session value.
     *
     * @return string
     */
    public function getName()
    {
        return 'login_web' . sha1(static::class);
    }

    /**
     * Attempt to get the guard from the local cache.
     *
     * @param  string|null  $name 
     */
    public function guard($name = null)
    {
        $this->guards = $this->getConfig($name);

        return $this;
    }

    protected function getGuard()
    {
        $name = $this->guards ?: $this->getConfig($this->getDefaultDriver());

        return   $name;
    }

    protected function getConfig($name)
    {
        return config('auth.providers.' . $name);
    }

    /**
     * Get the default authentication driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return config('auth.defaults.provider');
    }

    /**
     * Update the session with the given ID.
     *
     * @param  string  $id
     * @return void
     */
    protected function updateSession($id)
    {
        $this->session->put($this->getName(), $id);
    }
}
