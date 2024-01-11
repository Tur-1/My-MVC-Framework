<?php

namespace App\Providers;

use TurFramework\Core\Facades\Route;
use TurFramework\Core\Application\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    public function register()
    {

        Route::group('app/routes/web.php');
    }
}
