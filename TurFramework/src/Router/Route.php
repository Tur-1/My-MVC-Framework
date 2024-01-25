<?php

namespace TurFramework\Router;

use Closure;
use TurFramework\Http\Request;
use TurFramework\Router\MiddlewareResolver;
use TurFramework\Router\RouteResolverTrait;

class Route
{
    use RouteResolverTrait;

    /**
     * Handle the $request 
     * 
     * @param Request $request 
     * @param RouteCollection $routes 
     */
    public static function resolve($request, $route)
    {
        $self = new self();

        MiddlewareResolver::handle($route['middleware'], $request);


        return $self->runRoute($route);
    }

    private function runRoute($route)
    {

        // If the action is a callable function, execute it
        if ($this->actionReferencesClosure($route['action'])) {
            $this->resolveClosureAction($route);
            return;
        }

        $this->resolveControllerAction($route);
    }


    private function actionReferencesClosure($action)
    {
        return $action instanceof Closure;
    }
}
