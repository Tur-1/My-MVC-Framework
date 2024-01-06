<?php

namespace TurFramework\Core\Router\Exceptions;


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
}
