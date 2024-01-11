<?php

namespace App\Providers;

use App\Services\ExampleService;
use App\Services\ExampleServiceInterface;
use TurFramework\Core\Application\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /** 
     * Register any application services.
     *
     */
    public function register()
    {
        /**
         *  an axample for binding a service
         *  $this->bind(interface::class, service::class);
         */

        $this->app->bind(ExampleServiceInterface::class, ExampleService::class);
    }
}
