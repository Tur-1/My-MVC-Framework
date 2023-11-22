<?php

namespace TurFramework\Core\Router;

use TurFramework\Core\Exceptions\HttpResponseException;

class MethodNotAllowedHttpException extends \Exception
{
    protected $message;
    protected $route;
    protected $routeMethod;
    protected $requestMethod;
    protected $code = 404;

    public function __construct($requestMethod, $route, $routeMethod)
    {
        $this->message = "The $requestMethod method is not supported for route $route. Supported methods: $routeMethod";
    }
}
