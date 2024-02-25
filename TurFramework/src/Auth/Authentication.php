<?php

namespace TurFramework\Auth;

use TurFramework\Session\Store;
use TurFramework\Auth\UserProvider;
use TurFramework\Validation\ValidationMessages;

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
     * @var \Illuminate\Contracts\Session\Session
     */
    protected $session;
    /**
     * The name of the guard. Typically "web".
     *
     * Corresponds to guard name in authentication configuration.
     *
     * @var string
     */
    public readonly string $name;

    /**
     * Indicates if the logout method has been called.
     *
     * @var bool
     */
    protected $loggedOut = false;

    /**
     * Create a new authentication guard.
     * @param  string  $name
     * @param  \TurFramework\Session\Store $session
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

    public function login($user)
    {
        $this->updateSession($user->id);
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
    }

    /**
     * Update the session with the given ID.
     *
     * @param  string  $id
     * @return void
     */
    protected function updateSession($id)
    {
        $this->session->put($this->getAuthSessionName(), $id);
    }
}
