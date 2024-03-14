<?php

namespace TurFramework\Session;

use TurFramework\Configurations\Repository;

class SessionManager
{

    protected $config;
    protected $session;
    public function __construct(Repository $config, Session $session)
    {

        $this->config = $config['session'];
        $this->session = $session;
    }

    public function start()
    {
        session_start();

        $this->session->start();
    }

    public function setSessionConfig()
    {

        $this->setSessionName();

        $this->setCookieParams();

        $this->storeSessionFile();

        return $this;
    }
    private function setSessionName()
    {

        session_name($this->config['name']);

        $this->session->setName($this->config['name']);
    }

    private function storeSessionFile()
    {
        session_save_path($this->config['files']);
    }
    private function setCookieParams()
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
