<?php

namespace App\Http\Middleware;

use TurFramework\Http\Request;
use App\Http\Middleware\Middleware;

class ExampleMiddleware implements Middleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request)
    {
    }
}
