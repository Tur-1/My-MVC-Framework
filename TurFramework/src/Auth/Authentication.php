<?php

namespace TurFramework\Auth;

use TurFramework\Auth\UserProvider;
use TurFramework\Session\Store;

class Authentication
{

    protected $session;

    protected $userProvider;

    /**
     * Create a new authentication guard.
     *
     * @param  \TurFramework\Session\Store $session
     * @param  \TurFramework\Auth\UserProvider $provider
     * @return void
     */
    public function __construct(Store $session, UserProvider $userProvider)
    {
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
            dd($user);
        }
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
        return $this->userProvider->verifyPassword($user->password, $credentials['password']);
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
