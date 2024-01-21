<?php

namespace App\Http\Middleware;

use Closure;
use TurFramework\Exceptions\HttpResponseException;
use TurFramework\Http\Request;

class Auth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request)
    {
        $x = 34;

        if ($x !== 34) {
            throw new HttpResponseException(message: 'Auth message', code: 404);
        }
    }
}
