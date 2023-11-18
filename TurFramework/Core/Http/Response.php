<?php

namespace TurFramework\Core\Http;

class Response
{

    private array $server;

    public function __construct()
    {
        $this->server = $_SERVER;
    }
}
