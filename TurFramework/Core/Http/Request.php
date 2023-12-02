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

    /**
     * Get the requested URI (Uniform Resource Identifier) from the server.
     *
     * @return string The requested URI path.
     */
    public function getUri()
    {
        return parse_url($this->getServer('REQUEST_URI'), PHP_URL_PATH);
    }

    /**
     * Get the request method used for the current request.
     *
     * @return string The request method (e.g., GET, POST, PUT, DELETE, etc.).
     */
    public function getMethod()
    {
        return $this->getServer('REQUEST_METHOD');
    }

    /**
     * Get the decoded path from the requested URI.
     *
     * @return string The decoded path from the requested URI.
     */
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
    /**
     * Check if the current request path matches a given pattern.
     *
     * @param string $pattern The pattern to match against the request path.
     *
     * @return bool True if the request path matches the pattern, false otherwise.
     */
    public function is($pattern)
    {
        // Get the current path from the request
        $path = $this->getPath();

        // Check if the pattern ends with a wildcard *
        $endsWithWildcard = substr($pattern, -2) === '/*';

        // If the pattern ends with a wildcard *, remove it
        if ($endsWithWildcard) {
            $pattern = substr($pattern, 0, -2);
        }

        // Perform the comparison to check if the path starts with the pattern

        // If the pattern is just '/', check if the path is also '/'
        if ($pattern === '/') {
            return $path === '/';
        }

        // Check if the path starts with the pattern (including /)
        // Also, if the path matches the pattern exactly
        return strpos($path, $pattern) === 0 || $path === $pattern;
    }

    /**
     * Check if the request method is POST.
     *
     * @return bool True if the request method is POST, false otherwise.
     */
    public function isPost(): bool
    {
        // Check if the request method is POST
        return $this->isMethod(self::METHOD_POST);
    }
    /**
     * Check if the request method is GET.
     *
     * @return bool True if the request method is GET, false otherwise.
     */
    public function isGet(): bool
    {
        return $this->isMethod(self::METHOD_GET);
    }


    /**
     * Get valid HTTP request methods.
     *
     * @return array An array containing valid HTTP request methods
     */
    public function getValidMethods()
    {
        return [
            self::METHOD_GET,
            self::METHOD_POST,
            self::METHOD_PUT,
            self::METHOD_DELETE,
        ];
    }
    /**
     * Retrieve all request parameters from both GET and POST methods, sanitized against special characters.
     *
     * @return array An array containing all sanitized request parameters.
     */
    public function all()
    {
        $body = [];

        // Check if the request method is GET and sanitize its parameters
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        // Check if the request method is POST and sanitize its parameters
        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        // Return the sanitized request parameters or an empty array if none found
        return $body ?? [];
    }
    public function has($param): bool
    {
        return array_key_exists($param, $this->all());
    }
    /**
     * Get the $_SERVER values.
     *
     * @param string $key 
     *
     * @return mixed|null The value from the server array corresponding to the given key, or null if the key is not found.
     */
    private function getServer($key)
    {
        return $this->server[$key] ?? null;
    }
}
