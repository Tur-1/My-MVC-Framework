<?php

namespace App\Http;

use TurFramework\Http\HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'guest' => \App\Http\Middleware\Guest::class,
        'auth' => \App\Http\Middleware\Authenticated::class,
        'guest:admins' => \App\Http\Middleware\GuestAdmin::class,
        'auth:admins' => \App\Http\Middleware\AuthenticatedAdmin::class,
    ];
}
