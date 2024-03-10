<?php

namespace App\Http\Middleware;

use TurFramework\Facades\Auth;
use TurFramework\Http\Request;
use TurFramework\Http\Middleware\Middleware;

class AuthenticatedAdmin implements Middleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request)
    {
     
            if (Auth::guard('admins')->guest()) {
                return  redirect()->to(route('admin.login'));
              
            }
    
    }
}
