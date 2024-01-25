<?php

namespace TurFramework\Router;

use TurFramework\Support\UrlGenerator;
use TurFramework\Support\ServiceProvider;


class RoutingServiceProvider extends ServiceProvider
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

        $this->app->bind('url', function ($app) {
            return new UrlGenerator(
                $app->make('router')->getRouteCollection(),
                $app->make('request')
            );
        });
    }
}
