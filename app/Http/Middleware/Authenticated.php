<?php

namespace App\Http\Middleware;

use TurFramework\Facades\Auth;
use TurFramework\Http\Request;
use TurFramework\Http\Middleware\Middleware;

class Authenticated implements Middleware
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
