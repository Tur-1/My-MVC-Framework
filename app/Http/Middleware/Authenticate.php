<?php

namespace App\Http\Middleware;

use TurFramework\Facades\Auth;
use TurFramework\Http\Request;

class Authenticate implements MiddlewareInterface
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request)
    {

        if (Auth::guest()) {
            return redirect()->to('/');
        }
    }
}
