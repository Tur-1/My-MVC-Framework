<?php

namespace App\Http\Middleware;

use TurFramework\Http\Request;

interface MiddlewareInterface
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request);
}
