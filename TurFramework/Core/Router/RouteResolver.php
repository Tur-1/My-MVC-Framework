<?php

namespace TurFramework\Core\Router;

use ReflectionClass;
use TurFramework\Core\Router\Exceptions\RouteException;
use TurFramework\Core\Router\Exceptions\RouteNotFoundException;

class RouteResolver
{

    public $routeParams = [];
    /**
     * Handle the resolved action 
     *
     * @param mixed $action 
     */
    public static function resolve($path, $requestMethod, $routes)
    {


        $instance = new self();

        $route = $instance->matchRoute($path, $requestMethod, $routes);


        $instance->resolveController($route);
    }


    /**
     * Retrieve the route handler associated with the given method and path.
     *
     * @param string $path 
     * @param string $requestMethod 
     * @param array $routes 
     * @return array|null 
     * 
     * @throws RouteException
     * @throws RouteNotFoundException
     */
    public function matchRoute($path, $requestMethod, $routes)
    {
        $handler = null;

        foreach ($routes as $key => $route) {

            // Replace route parameters with regex patterns to match dynamic values
            $pattern = preg_replace_callback('/\{(\w+)\}/', function ($matches) {

                return '(?P<' . $matches[1] . '>[^/]+)';
            }, $key);

            $pattern = str_replace('/', '\/', $pattern);

            $pattern = '/^' . $pattern . '$/';

            // Check if the requested path matches the route pattern
            if (preg_match($pattern, $path, $matches)) {

                $handler = $route;

                // Store route parameters and their values
                $handler['parameters'] = array_intersect_key($matches, array_flip($handler['parameters']));
                $this->routeParams = $handler['parameters'];
                break;
            }
        }

        // Check if the route method is not allowed
        if ($this->isMethodNotAllowedForRoute($handler, $requestMethod)) {
            throw  RouteException::methodNotAllowed($requestMethod, $path, $handler['method']);
        }


        // Check if no action is associated with the route, throw RouteNotFoundException.
        if (is_null($handler)) {
            throw new RouteNotFoundException();
        }

        return $handler;
    }


    public function resolveController($route)
    {


        // If the action is a callable function, execute it
        if (is_callable($route['action'])) {
            $action = new \ReflectionFunction($route['action']);

            $parameters = $action->getParameters() ?? [];

            $resolvedDependencies = $this->resolveMethodDependencies($parameters);

            $this->invokeControllerMethod($route['action'], $resolvedDependencies);
            return;
        }

        // Check if the controller class exists
        if ($this->isControllerNotExists($route['controller'])) {
            throw RouteException::targetClassDoesNotExist($route['controller']);
        }

        $controller = app()->resolve($route['controller']);

        $method = $route['action'];


        $reflectorController = new ReflectionClass($controller);

        $parameters = $reflectorController->getMethod($method)?->getParameters() ?? [];

        // Check if the method exists in the controller
        if (!$reflectorController->hasMethod($method)) {
            throw RouteException::methodDoesNotExist($reflectorController->getShortName(),  $method);
        }

        $resolvedDependencies = $this->resolveMethodDependencies($parameters);

        return $this->invokeControllerMethod([$controller, $method], $resolvedDependencies);
    }


    private function invokeControllerMethod($callable, $resolveDependencies)
    {
        return call_user_func($callable, ...array_merge(array_filter($resolveDependencies), array_filter($this->routeParams)));
    }



    public function resolveMethodDependencies($parameters)
    {
        $dependencies = [];

        foreach ($parameters as  $parameter) {
            $type = $parameter->getType();

            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                $dependencies[] = app()->resolve($parameter->getType()?->getName());
            }
        }

        return $dependencies;
    }
    /**
     * Checks if the controller class doesn't exist.
     * @param string $controllerClass Name of the controller class.
     * @return bool
     */
    public function isControllerNotExists($controllerClass)
    {
        return !is_null($controllerClass) && !class_exists($controllerClass);
    }

    // Method to check if the requested method matches the route method
    private function isMethodNotAllowedForRoute($route, $requestMethod)
    {

        if (!is_null($route) && $route['method'] !==  $requestMethod) {
            return true;
        }

        return false;
    }
}
