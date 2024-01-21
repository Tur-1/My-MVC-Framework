<?php

namespace TurFramework\Router;

use Closure;
use App\Http\Kernel;
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
    public static function handle($request, $routes)
    {
        $self = new self();

        $route = $routes->match($request->getPath(), $request->getMethod());


        MiddlewareResolver::handle($route['middleware'], $request);


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


    private function actionReferencesClosure($action)
    {
        return $action instanceof Closure;
    }
}
