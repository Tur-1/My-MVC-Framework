<?php

namespace TurFramework\src\Http;

class Response
{

    private array $server;

    public function __construct()
    {
        $this->server = $_SERVER;
    }
}
