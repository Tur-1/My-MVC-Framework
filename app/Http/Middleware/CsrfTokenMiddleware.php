<?php

namespace App\Http\Middleware;

use TurFramework\Http\Request;

class CsrfTokenMiddleware
{
    /**
     * handle.
     *
     * @param	Request	$request		
     * @return	mixed
     */
    public function handle(Request $request)
    {

        if ($request->isPost() && !$request->has('csrf_token')) {
            throw new \Exception("CSRF token not found");
        }
    }
}
