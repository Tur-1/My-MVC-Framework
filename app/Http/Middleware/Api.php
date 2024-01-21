<?php

namespace App\Http\Middleware;

use Closure;
use TurFramework\Http\Request;
use TurFramework\Exceptions\HttpResponseException;

class Api
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request)
    {
        $x = 34;

        if ($x !== 34) {
            throw new HttpResponseException(message: 'Api message', code: 404);
        }
    }
}
