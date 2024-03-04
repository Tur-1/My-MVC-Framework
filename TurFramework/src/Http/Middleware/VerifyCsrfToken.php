<?php

namespace TurFramework\Http\Middleware;

use TurFramework\Http\Request;

class VerifyCsrfToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request)
    {

        if ($request->isPost() && $this->requestTokenDoesNotMatchSessionToken($request)) {

            abort(419);
        }
    }

    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @param  \TurFramework\Http\Request  $request
     * @return bool
     */
    protected function requestTokenDoesNotMatchSessionToken($request)
    {
        $token = $this->getTokenFromRequest($request);

        return $token !== session()->token();
    }

    protected function getTokenFromRequest($request)
    {
        return $request->has('_token') ? $request->get('_token') : null;
    }
}
