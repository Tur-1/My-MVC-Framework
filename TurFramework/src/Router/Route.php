<?php

namespace TurFramework\Router;

use Closure;
use TurFramework\Http\Request;
use TurFramework\Router\RouteResolverTrait;
use TurFramework\Router\Exceptions\RouteException;

class Route
{
    use RouteResolverTrait;

    /**
     * Handle the resolved action 
     * 
     * @param Request $request 
     * @param Kernel $kernelmiddlewares
     * @param RouteCollection $routes 
     */
    public static function handle($request, $kernelmiddlewares, $routes)
    {
        $self = new self();

        $route = $routes->match($request->getPath(), $request->getMethod());


        $self->resolveMiddleware($kernelmiddlewares, $route['middleware'], $request);


        return $self->resolve($route);
    }

    private function resolve($route)
    {

        // If the action is a callable function, execute it
        if ($this->actionReferencesClosure($route['action'])) {
            $this->resolveClosureAction($route);
            return;
        }

        $this->resolveControllerAction($route);
    }
    private function resolveMiddleware($middlewares, $routeMiddleware, $request)
    {
        if (!is_null($routeMiddleware)) {
            foreach ($routeMiddleware as $key => $value) {
                if (!isset($middlewares[$value])) {
                    throw RouteException::targetClassDoesNotExist($value);
                }

                $middlewareClass = new $middlewares[$value]();
                // Apply the middleware to the request.
                $middlewareClass->handle($request);
            }
        }
    }


    private function actionReferencesClosure($action)
    {
        return $action instanceof Closure;
    }
}
