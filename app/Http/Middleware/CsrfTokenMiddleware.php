<?php

namespace App\Http\Middleware;

use TurFramework\Http\Request;

class CsrfTokenMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request)
    {
        if (
            $request->isPost() &&
            !$request->has('_token')
        ) {
            abort(419);
        }
    }
}
