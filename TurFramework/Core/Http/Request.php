<?php

namespace TurFramework\Core\Http;

class Request
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_DELETE = 'DELETE';
    private array $server;

    public function __construct()
    {
        $this->server = $_SERVER;
    }

    public function getUri()
    {
        return parse_url($this->getServer('REQUEST_URI'), PHP_URL_PATH);
    }

    public function getMethod()
    {
        return $this->getServer('REQUEST_METHOD');
    }

    public function getPath()
    {
        $uri = urldecode($this->getUri());

        return $uri;
    }

    /**
     * Check if the request method matches the
     * given method.
     *
     * @param string $method
     *
     * @return string
     */
    public function isMethod($method)
    {
        $method = strtoupper($method);

        if (!in_array($method, $this->getValidMethods())) {
            throw new \Exception('Invalid Request Method!');
        }

        return $this->getMethod() == $method ? true : false;
    }

    public function getValidMethods()
    {
        return [
            self::METHOD_GET,
            self::METHOD_POST,
            self::METHOD_PUT,
            self::METHOD_DELETE,
        ];
    }

    private function getServer($key)
    {
        return $this->server[$key];
    }
}
