<?php

namespace TurFramework\Session;

class SessionManager
{

    protected $config;
    protected $session;
    public function __construct($config, $session)
    {
        $this->config = $config['session'];
        $this->session = $session;
    }

    public function start()
    {


        ini_set('session.use_only_cookies', 1);

        $this->setCookieParams();

        $this->setSessionName();


        session_start();

        $this->session->start();
    }

    public function setSessionName()
    {

        session_name($this->config['session_name']);

        $this->session->setName($this->config['session_name']);
    }

    public function setCookieParams()
    {

        session_set_cookie_params(
            [
                'lifetime' =>   60 * 60 * 24 * 365 * 3,
                'secure' =>  $this->config['secure'] ?? true,
                'httponly' =>  $this->config['http_only'] ?? true,
                'samesite' => $this->config['samesite'] ?? 'lax'
            ]
        );
    }
}
