<?php

namespace App\Providers;

use App\Services\ExampleService;
use App\Services\ExampleServiceInterface;
use TurFramework\Core\Container\Container;


class AppServiceProvider extends Container
{
    /** 
     * Register any application services.
     * @return	void
     */
    public function register()
    {
        /**
         *  an axample for binding a service
         *  $this->bind(interface::class, service::class);
         */

        $this->bind(ExampleServiceInterface::class, ExampleService::class);
    }
}
