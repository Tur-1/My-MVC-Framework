<?php

namespace TurFramework\Auth;

use TurFramework\Auth\Authentication;
use TurFramework\Support\ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerUrlGenerator();
    }
    /**
     * Register the URL generator service.
     *
     * @return void
     */
    protected function registerUrlGenerator()
    {

        $this->app->bind('auth', function ($app) {
            return new Authentication($app->make('session'));
        });
    }
}
