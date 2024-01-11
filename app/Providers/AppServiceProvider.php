<?php

namespace App\Providers;

use App\Services\ExampleService;
use App\Services\ExampleServiceInterface;
use TurFramework\src\Application\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /** 
     * Register any application services.
     *
     */
    public function register()
    {
        /**
         *  $this->app->bind(interface::class, service::class);
         */


        $this->app->bind(ExampleServiceInterface::class, ExampleService::class);
    }
}
