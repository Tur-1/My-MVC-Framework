<?php

namespace TurFramework\src\Router\Exceptions;


class RouteException extends \Exception
{

    public static function targetClassDoesNotExist(string $targetClass)
    {
        return new self("Target class [ " . $targetClass . " ] does not exist");
    }

    public static function methodDoesNotExist(string $targetClass, string $targetMethod)
    {
        return new self("Method class [ " . $targetClass . '::' . $targetMethod . " ] does not exist");
    }

    public static function methodNotAllowed($requestMethod, $route, $routeMethod)
    {
        return new self("The $requestMethod method is not supported for route $route. Supported methods: $routeMethod");
    }

    public static function routeNotDefined($routeName)
    {
        return new self("Route [ $routeName ] not defined.");
    }
    public static function missingRequiredParameters($routeName, $uri, $parameter)
    {
        return new self("Missing required parameter for [Route: $routeName] [URI: " . $uri . " ]  [ Missing parameter: $parameter ].");
    }

    public static function routeFilesNotFound()
    {
        return new self("No route files found in routes directory");
    }
}
