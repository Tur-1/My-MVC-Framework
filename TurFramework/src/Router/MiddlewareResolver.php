<?php

namespace TurFramework\Router;

use TurFramework\Router\Exceptions\RouteException;

class MiddlewareResolver
{

    public static function handle($globalMiddleware, $routeMiddleware, $route, $request)
    {
        $instance = new self();


        $instance->resolveGlobalMiddleware($globalMiddleware, $request);

        if (!is_null($route['middleware'])) {
            $instance->resolveRouteMiddleware($route, $routeMiddleware, $request);
        }
    }
    private function resolveRouteMiddleware($route, $routeMiddleware, $request)
    {

        foreach ($route['middleware'] as  $middleware) {

            if (!isset($routeMiddleware[$middleware])) {
                throw RouteException::targetClassDoesNotExist($middleware);
            }

            $middlewareClass = new $routeMiddleware[$middleware]();

            if (!method_exists($middlewareClass, 'handle')) {
                throw RouteException::methodDoesNotExist($routeMiddleware[$middleware], 'handle');
            }

            $middlewareClass->handle($request);
        }
    }
    private function resolveGlobalMiddleware($globalMiddleware, $request)
    {
        foreach ($globalMiddleware as $value) {

            $middlewareClass = new $value();
            if (!method_exists($middlewareClass, 'handle')) {
                throw RouteException::methodDoesNotExist($value, 'handle');
            }
            $middlewareClass->handle($request);
        }
    }
}
