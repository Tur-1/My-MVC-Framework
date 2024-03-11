<?php

namespace TurFramework\Router;

use TurFramework\Router\Exceptions\RouteException;

trait RouteResolverTrait
{
    public function resolveMethodDependencies($parameters)
    {
        $dependencies = [];

        foreach ($parameters as  $parameter) {
            $type = $parameter->getType();

            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                $dependencies[] = app()->make($parameter->getType()?->getName());
            }
        }

        return $dependencies;
    }

    protected function resolveControllerAction($route)
    {
        // Check if the controller class exists
        if ($this->isControllerNotExists($route['controller'])) {
            throw RouteException::targetClassDoesNotExist($route['controller']);
        }

        $controller = app()->make($route['controller']);

        $method = $route['action'];


        $reflectorController = new \ReflectionClass($controller);

        $controllerMethod = $reflectorController->getMethod($method);

        // Check if the method exists in the controller
        if (!$reflectorController->hasMethod($method)) {
            throw RouteException::methodDoesNotExist($reflectorController->getShortName(),  $method);
        }

        $parameters = $controllerMethod->getParameters() ?? [];


        $resolvedDependencies = $this->resolveMethodDependencies($parameters);

        $this->invokeControllerMethod([$controller, $method], $resolvedDependencies, $route['parameters']);
    }

    private function resolveClosureAction($route)
    {
        $action = new \ReflectionFunction($route['action']);

        $parameters = $action->getParameters() ?? [];

        $resolvedDependencies = $this->resolveMethodDependencies($parameters);

        $this->invokeControllerMethod($route['action'], $resolvedDependencies, $route['parameters']);
    }

    private function invokeControllerMethod($callable, $resolveDependencies, $routeParams)
    {
        call_user_func($callable, ...array_merge(array_filter($resolveDependencies), array_filter($routeParams)));
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
}
