<?php

namespace App\Http\Middleware;

use TurFramework\Facades\Auth;
use TurFramework\Http\Request;
use TurFramework\Http\Middleware\Middleware;

class RedirectIfAuthenticated implements Middleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($request->is('/admin*')) {
                    return redirect()->to(route('admin.dashboard'));
                } else {
                    return redirect()->to('/');
                }
            }
        }
    }
}
