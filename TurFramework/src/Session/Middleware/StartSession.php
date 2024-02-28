<?php

namespace TurFramework\Session\Middleware;

use TurFramework\Http\Request;

class StartSession
{

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request)
    {

        $session = app('session');

        $session->setName('TurFramework_session');

        $this->start($session);
    }


    public function start($session)
    {
        session_start();

        $session->start();
    }
}
