<?php

namespace TurFramework\src\Http;

class Request
{
    public const METHOD_GET = 'get';
    public const METHOD_POST = 'post';
    public const METHOD_PUT = 'put';
    public const METHOD_DELETE = 'delete';
    private array $server;

    public function __construct()
    {
        $this->server = $_SERVER;
    }

    public function getUri()
    {
        return parse_url($this->getServer('REQUEST_URI'));
    }

    public function getMethod()
    {
        return strtolower($this->getServer('REQUEST_METHOD'));
    }
    public function getPath()
    {
        $uri  = $this->getUri();

        return $uri['path'] ?? '/';
    }


    private function getServer($key)
    {
        return $this->server[$key];
    }
}
