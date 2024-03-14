<?php

namespace TurFramework\Session\Middleware;

use TurFramework\Http\Request;
use TurFramework\Session\SessionManager;

class StartSession
{
    /**
     * The session manager.
     *
     * @var \TurFramework\Session\SessionManager
     */
    protected $manager;


    public function __construct(SessionManager $sessionManager)
    {
        $this->manager = $sessionManager;
    }
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request)
    {

        $this->manager->setSessionConfig()->start();
    }
}
