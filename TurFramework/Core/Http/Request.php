<?php

namespace TurFramework\Core\Http;

use TurFramework\Core\Facades\Route;

class Request
{
    private static  $instance = null;
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_DELETE = 'DELETE';


    public function __construct()
    {
    }
    public static function getInstance()
    {
        // If no instance exists, create a new one
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Send the given request through the middleware / router.
     *
     * @param  Request  $request
     * 
     */
    public function sendRequestThroughRouter()
    {
        Route::loadRotues()->resolve($this);
    }


    /**
     * Get the requested URI (Uniform Resource Identifier) from the server.
     *
     * @return string The requested URI path.
     */
    public function getUri(): string
    {
        $uri = urldecode(
            parse_url($this->getServer('REQUEST_URI'), PHP_URL_PATH)
        );

        return $uri;
    }
    /**
     * Get the host name from the current request.
     *
     * @return string The host name
     */
    public function getHost(): string
    {
        // Retrieve the host from the HTTP_HOST server variable
        return $this->getServer('HTTP_HOST') ?? '';
    }

    /**
     * Get the previous path from the referer URL in the server array.
     *
     * @return string|null The path of the previous URL if available, otherwise null
     */
    public function previousUrl(): string|null
    {
        // Get the referer URL from the server array
        $referer = $this->getServer('HTTP_REFERER');

        // Parse the referer URL to extract its components
        $parsedReferer = parse_url($referer);

        // Get the path from the referer URL
        $refererPath = $parsedReferer['path'] ?? '';

        return $refererPath;
    }

    /**
     * Get the previous URL with query string from the referer URL in the server array.
     *
     * @return string|null The previous URL with query string if available, otherwise null
     */
    public function previousUrlWithQuery(): string|null
    {
        // Get the referer URL from the server array
        $referer = $this->getServer('HTTP_REFERER');

        if (!$referer) {
            return null; // Return null if no referer URL is found
        }

        // Parse the referer URL to extract its components
        $parsedReferer = parse_url($referer);

        // Get the path and query string from the referer URL
        $refererPath = $parsedReferer['path'] ?? '';
        $refererQueryString = $parsedReferer['query'] ?? '';

        // Construct the full URL with the path and query string
        $urlWithQuery = $refererPath;

        if (!empty($refererQueryString)) {
            $urlWithQuery .= '?' . $refererQueryString;
        }

        return $urlWithQuery;
    }
    /**
     * Get the protocol (HTTP or HTTPS) of the current request.
     *
     * @return string The protocol
     */
    public function getProtocol(): string
    {


        // Check if HTTPS is on to determine the protocol
        return !is_null($this->getServer('HTTPS')) && $this->getServer('HTTPS') === 'on' ? 'https://' : 'http://';
    }

    /**
     * Get the full URL of the current request, including the query string.
     *
     * @return string The full URL with query string
     */
    public function fullUrlWithQuery(): string
    {
        // Retrieve the protocol, host, and URI using their respective methods
        $protocol = $this->getProtocol();
        $host = $this->getHost();
        $uri = $this->getUri();

        // Get the query string
        $queryString = $this->getQueryString();

        // Construct the full URL with the query string
        $urlWithQuery = $protocol . $host . $uri;

        // Append the query string if it exists
        if (!empty($queryString)) {
            $urlWithQuery .= '?' . $queryString;
        }

        return $urlWithQuery;
    }
    /**
     * Get the query string from the current request.
     *
     * @return string The query string
     */
    public function getQueryString(): string
    {

        return !is_null($this->getServer('QUERY_STRING')) ? $this->getServer('QUERY_STRING') : '';
    }
    /**
     * Get the full URL of the current request.
     *
     * @return string The full URL
     */
    public function fullUrl(): string
    {
        // Retrieve the protocol and host using the respective methods
        $protocol = $this->getProtocol();
        $host = $this->getHost();

        // Retrieve the URI from the REQUEST_URI server variable
        $uri = $this->getUri();

        // Concatenate protocol, host, and URI to reconstruct the full URL
        return $protocol . $host . $uri;
    }
    /**
     * Get the request method used for the current request.
     *
     * @return string The request method (e.g., GET, POST, PUT, DELETE, etc.).
     */
    public function getMethod(): string
    {
        return $this->getServer('REQUEST_METHOD');
    }

    /**
     * Get the decoded path from the requested URI.
     *
     * @return string The decoded path from the requested URI.
     */
    public function getPath(): string
    {
        return $this->getUri();
    }

    /**
     * Check if the request method matches the
     * given method.
     *
     * @param string $method
     *
     * @return bool
     */
    public function isMethod(string $method): bool
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
    public function is(string $url): bool
    {
        // Get the current path from the request
        $path = $this->getPath();


        // Check if the pattern ends with a wildcard *
        $endsWithWildcard = substr($url, -2) === '/*';

        // If the pattern ends with a wildcard *, remove it
        if ($endsWithWildcard) {
            $url = substr($url, 0, -2);
        }

        // Perform the comparison to check if the path starts with the pattern

        // If the pattern is just '/', check if the path is also '/'
        if ($url === '/') {
            return $path === '/';
        }

        // Check if the path starts with the pattern (including /)
        // Also, if the path matches the pattern exactly
        return strpos($path, $url) === 0 || $path === $url;
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
    public function getValidMethods(): array
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
    public function all(): array
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
    /**
     * Retrieve a specific sanitized input parameter by its key.
     *
     * @param string $key The key of the input parameter to retrieve
     * @return mixed|null The value of the input parameter or default if not found
     */
    public function get(string $key, $default = null)
    {
        // Retrieve all sanitized request parameters
        $allParams = $this->all();

        // Check if the provided input key exists 
        // If found, return the value; otherwise, return default
        if ($this->has($key)) {
            return $allParams[$key];
        }

        return $default;
    }

    /**
     * Check if a sanitized input parameter exists by its key.
     *
     * @param string $param The key of the input parameter to check
     * @return bool True if the parameter exists, false otherwise
     */
    public function has(string $key): bool
    {
        // Retrieve all sanitized request parameters into an array
        $allParams = $this->all();

        // Check if the provided parameter key exists in the sanitized parameters array
        return array_key_exists($key, $allParams);
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
        return Server::get($key);
    }

    public static function __callStatic($method, $args)
    {

        return static::$instance->{$method}(...$args);
    }

    public function __call($method, $args)
    {

        return static::$instance->{$method}(...$args);
    }
}
