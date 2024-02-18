<?php

namespace App\Providers;

use TurFramework\Facades\Route;
use TurFramework\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    public function register()
    {


        /**
         * Use the Route::group() method to load route files.
         */

        // Load web routes from the 'app/routes/web.php' file.
        Route::group(base_path('app/routes/web.php'));

        // Load API routes from the 'app/routes/api.php' file.
        Route::group(base_path('app/routes/api.php'));

        Route::group(base_path('app/routes/auth.php'));
    }
}
