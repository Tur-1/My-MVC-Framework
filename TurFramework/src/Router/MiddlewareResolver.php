<?php

namespace TurFramework\Router;

use App\Http\Kernel;
use TurFramework\Router\Exceptions\RouteException;

class MiddlewareResolver extends Kernel
{

    public static function handle($routeMiddleware, $request)
    {
        $instance = new self();

        $instance->resolveGlobalMiddleware($request);

        if (!is_null($routeMiddleware)) {
            $instance->resolveRouteMiddleware($routeMiddleware, $request);
        }
    }
    private function resolveRouteMiddleware($routeMiddleware, $request)
    {


        foreach ($routeMiddleware as $key => $value) {
            if (!isset($this->routeMiddleware[$value])) {
                throw RouteException::targetClassDoesNotExist($value);
            }

            $middlewareClass = new $this->routeMiddleware[$value]();

            if (!method_exists($middlewareClass, 'handle')) {
                throw RouteException::methodDoesNotExist($this->routeMiddleware[$value], 'handle');
            }
            $middlewareClass->handle($request);
        }
    }
    private function resolveGlobalMiddleware($request)
    {
        foreach ($this->middleware as $value) {
            $middlewareClass = new $value();
            if (!method_exists($middlewareClass, 'handle')) {
                throw RouteException::methodDoesNotExist($value, 'handle');
            }
            $middlewareClass->handle($request);
        }
    }
}
