<?php

namespace TurFramework\Auth;

use TurFramework\Auth\Authentication;
use TurFramework\Support\ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerAuthenticator();
    }

    /**
     * Register the authenticator services.
     *
     * @return void
     */
    protected function registerAuthenticator()
    {
        $this->app->bind('auth', fn ($app) => new AuthManager($app));

    }
}
