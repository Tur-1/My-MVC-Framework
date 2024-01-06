<?php

namespace TurFramework\Core\Router;

class RouteAction
{
    /**
     * Creates a new route array based on the provided method, route, and callable.
     *
     * @param string $method 
     * @param string $route 
     * @param array  $action
     *
     * @return array Returns an array representing the new route.
     */
    public static function createNewRoute($method, $route, $action, $name = null)
    {
        return  [
            'uri' => $route,
            'method' => $method,
            'controller' => $action['controller'],
            'action' =>  $action['action'],
            'parameters' => static::extractRouteParameters($route),
            'name' => $name,
        ];
    }

    /**
     * Extracts parameters from the provided route URI pattern.
     * 
     * @param string $route
     * @return array $parameters
     */
    public static function extractRouteParameters(string $route)
    {

        $parameters = [];
        $routeParts = array_filter(explode('/', $route));

        foreach ($routeParts as $part) {
            if (str_starts_with($part, '{') &&  str_ends_with($part, '}')) {
                $parameters[] = substr($part, 1, -1); // Extracts the parameter name without braces
            }
        }

        return  $parameters;
    }

    /**
     * Determines the callable format based on the input and returns it as an array.
     *
     * @param mixed $action 
     *
     * @return array
     */
    public static function getAction($action, $controller)
    {
        if (!is_null($controller) && is_string($action)) {
            return ['controller' => $controller, 'action' =>  $action];
        }

        if (is_array($action)) {
            return ['controller' =>  $action[0], 'action' =>  $action[1]];
        }

        if (is_callable($action)) {
            return ['controller' => null, 'action' => $action];
        }
    }
}
