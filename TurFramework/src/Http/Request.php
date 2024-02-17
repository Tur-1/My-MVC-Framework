<?php

namespace TurFramework\Http;

use Attribute;
use TurFramework\Support\Arr;
use TurFramework\Validation\Validator;

class Request
{

    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_DELETE = 'DELETE';


    public function __construct()
    {
    }

    /**
     * Get the requested URI 
     *
     * @return string The requested URI path.
     */
    public function getUri(): string
    {
        $uri = urldecode(
            parse_url($this->getServer('REQUEST_URI'), PHP_URL_PATH)
        );

        return  trim($uri);
    }
    /**
     * Get the host name from the current request.
     *
     * @return string The host name
     */
    public function getHost(): string
    {

        return $this->getServer('HTTP_HOST') ?? '';
    }

    /**
     * Get the previous path from the referer URL in the server array.
     *
     * @return string|null 
     */
    public function previousUrl(): string|null
    {

        $referer = $this->getServer('HTTP_REFERER');

        // Parse the referer URL to extract its components
        $parsedReferer = parse_url($referer);

        $refererPath = $parsedReferer['path'] ?? '';

        return $refererPath;
    }

    /**
     * Get the previous path from the referer URL in the server array.
     *
     * @param array $rules
     * @param array $messages
     * @return array
     */
    public function validate(array $rules, array $messages = [])
    {

        $vaildator = new Validator($this->only(array_keys($rules)), $rules, $messages);

        $validatedRequest = $vaildator->validate();

        if ($vaildator->fails()) {
            session()->put('errors', $vaildator->getErrors());
            session()->flash('old', $validatedRequest);

            throw redirect()->back();
        }

        return $validatedRequest;
    }
    /**
     * Get the previous URL with query string from the referer URL in the server array.
     *
     * @return string|null
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
     * @return string 
     */
    public function fullUrlWithQuery(): string
    {

        $protocol = $this->getProtocol();
        $host = $this->getHost();
        $uri = $this->getUri();


        $queryString = $this->getQueryString();

        $urlWithQuery = $protocol . $host . $uri;

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

        $protocol = $this->getProtocol();
        $host = $this->getHost();

        $uri = $this->getUri();

        return $protocol . $host . $uri;
    }
    /**
     * Get the request method used for the current request.
     *
     * @return string The request method 
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

    public function only($keys)
    {
        return Arr::only($this->all(), $keys);
    }
    /**
     * Check if the request method matches the given method.
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
     * Get the current decoded path info for the request.
     *
     * @return string
     */
    private function decodedPath()
    {
        return rawurldecode($this->getPath());
    }
    /**
     * Check if the current request path matches a given pattern.
     *
     * @param string $path 
     *
     * @return bool
     */
    public function is(string $url): bool
    {
        // Get the current path from the request
        $path = $this->decodedPath();

        return $this->getPattren($url, $path);
    }

    private function getPattren($pattern, $value)
    {
        $value = (string) $value;

        if (!is_iterable($pattern)) {
            $pattern = [$pattern];
        }

        foreach ($pattern as $pattern) {
            $pattern = (string) $pattern;

            if ($pattern === $value) {
                return true;
            }

            $pattern = preg_quote($pattern, '#');

            $pattern = str_replace('\*', '.*', $pattern);

            if (preg_match('#^' . $pattern . '\z#u', $value) === 1) {
                return true;
            }
        }

        return false;
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
     * Retrieve all request parameters from both GET and POST methods.
     *
     * @return array request parameters.
     */
    public function all(): array
    {

        return $this->allInputsRequest();
    }
    /**
     * Retrieve a specific input parameter
     *
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {

        $allParams = $this->all();

        if ($this->has($key)) {
            return $allParams[$key];
        }

        return $default;
    }

    /**
     * Check if a input parameter exists
     *
     * @param string $key
     * @return bool 
     */
    public function has(string $key): bool
    {

        $allParams = $this->all();

        return array_key_exists($key, $allParams);
    }

    private function allInputsRequest()
    {
        $inputs = [];

        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $inputs[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $inputs[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $inputs;
    }
    /**
     * Get the $_SERVER values.
     *
     * @param string $key 
     *
     * @return mixed|null The value from the server
     */
    private function getServer($key)
    {
        return Server::get($key);
    }


    /**
     * Get an input element from the request.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return Arr::get($this->all(), $key);
    }
}
