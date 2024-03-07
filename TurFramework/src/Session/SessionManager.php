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
                'lifetime' =>  $this->getSessionLifeTime(),
                'path' =>  $this->config['path'] ?? '/',
                'domain' =>  $this->config['domain'],
                'secure' =>  $this->config['secure'] ?? true,
                'httponly' =>  $this->config['http_only'] ?? true,
                'samesite' => $this->config['samesite'] ?? 'lax'
            ]
        );
    }

    private function getSessionLifeTime()
    {

        if ($this->config['expire_on_close']) {
            return 0;
        }

        if ($this->config['expire_on_close'] == false && !$this->config['lifetime']) {
            return 60 * 60 * 24 * 365 * 2;
        }

        return $this->config['lifetime'];
    }
}
