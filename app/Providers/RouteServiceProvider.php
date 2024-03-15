<?php

namespace App\Providers;

use TurFramework\Facades\Route;
use TurFramework\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    public function register()
    {

        /**
         * Use the Route::load([]) method to load route files.
         */

        Route::load([
            base_path('app/Routes/web.php'),
            base_path('app/Routes/dashboard.php'),
            base_path('app/Routes/auth.php')
        ]);
    }
}
