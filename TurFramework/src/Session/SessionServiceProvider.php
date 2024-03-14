<?php

namespace TurFramework\Session;

use TurFramework\Support\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('session.manager', SessionManager::class);
    }
}
