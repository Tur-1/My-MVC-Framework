<?php

namespace TurFramework\Router;

use TurFramework\Database\Model;
use TurFramework\Router\Exceptions\RouteException;
use TurFramework\Support\Arr;

trait RouteResolverTrait
{
    public function resolveMethodDependencies($parameters, $routeParams)
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                $dependency = app()->make($type->getName());
                if ($dependency instanceof Model) {
                    $modelId = $routeParams[$parameter->getName()] ?? null;
                    $dependencies[$parameter->getName()] = $dependency::find($modelId);
                    unset($routeParams[$parameter->getName()]);
                } else {
                    $dependencies[$parameter->getName()] = $dependency;
                }
            }
        }
        return array_merge($dependencies, $routeParams);
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


        $resolvedDependencies = $this->resolveMethodDependencies($parameters, $route['parameters']);

        $this->invokeControllerMethod([$controller, $method], $resolvedDependencies);
    }



    private function invokeControllerMethod($callable, $resolveDependencies)
    {
        call_user_func_array($callable, $resolveDependencies);
    }
    private function resolveClosureAction($route)
    {
        $action = new \ReflectionFunction($route['action']);

        $parameters = $action->getParameters() ?? [];

        $resolvedDependencies = $this->resolveMethodDependencies($parameters);

        $this->invokeControllerMethod($route['action'], $resolvedDependencies, $route['parameters']);
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
