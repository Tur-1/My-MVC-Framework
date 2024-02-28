<?php

namespace TurFramework\Auth;

use TurFramework\Session\Store;
use TurFramework\Auth\UserProvider;

class Authentication
{
    /**
     * The user provider implementation.
     *
     * @var \TurFramework\Auth\UserProvider
     */
    protected $userProvider;
    /**
     * The session used by the guard.
     *
     * @var \TurFramework\Session\Store $session
     */
    protected $session;
    /**
     * The name of the guard. Typically "users".
     *
     * Corresponds to guard name in authentication configuration.
     *
     * @var string
     */
    public readonly string $name;

    protected $user;
    /**
     * Indicates if the logout method has been called.
     *
     * @var bool
     */
    protected $loggedOut = false;

    /**
     * Create a new authentication guard.
     * @param  string  $name
     * @param \TurFramework\Session\Store $session
     * @param  \TurFramework\Auth\UserProvider $provider
     * @return void
     */
    public function __construct($name, Store $session, UserProvider $userProvider)
    {
        $this->name = $name;
        $this->session = $session;
        $this->userProvider = $userProvider;
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
        $user = $this->userProvider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            $this->login($user);
            return true;
        }

        return false;
    }
    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return $this->session->get($this->getAuthSessionName());
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return !$this->session->get($this->getAuthSessionName());
    }

    public function login($user)
    {
        $this->updateSession($user->id);

        $this->setUser($user);
    }


    /**
     * Get the currently authenticated user.
     *
     */
    public function user()
    {
        if ($this->loggedOut) {
            return;
        }
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. 
        if (!is_null($this->user)) {
            return $this->user;
        }

        $userID = $this->session->get($this->getAuthSessionName()) ?? null;


        if (!is_null($userID)) {
            $this->user = $this->userProvider->retrieveById($userID);
        }


        return $this->user;
    }

    /**
     * Return the currently cached user.
     * 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the current user.
     *
     * @param  $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        $this->loggedOut = false;
        return $this;
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
        $validated = !empty($user) ? $this->userProvider->verifyPassword($user?->password, $credentials['password']) : false;


        return $validated;
    }

    /**
     * Get a unique identifier for the auth session value.
     *
     * @return string
     */
    public function getAuthSessionName()
    {
        return 'login_' . $this->name . '_' . sha1(static::class);
    }

    public function logout()
    {
        $this->session->remove($this->getAuthSessionName());

        $this->session->invalidate();

        $this->user = null;

        $this->loggedOut = true;
    }

    /**
     * Update the session with the given ID.
     *
     * @param  string  $id
     * @return void
     */
    protected function updateSession($id)
    {

        $this->session->regenerate();

        $this->session->put($this->getAuthSessionName(), $id);
    }
}
