<?php

namespace TurFramework\Core\Router;

use Closure;
use TurFramework\Core\Router\Exceptions\RouteException;

class RouteCollection
{
    /**
     * An array containing registered routes.
     *
     * @var array
     */
    public  $routes = [];
    /**
     * route
     *
     * @var string
     */
    public  $route;
    public  $router;
    /**
     * A look-up table of routes by their names.
     */
    protected $nameList = [];


    /**
     * Add a route to the internal routes collection 
     *
     */
    public function addRoute($method, $route, $action, $name = null)
    {

        return  $this->createNewRoute($method, $route, $this->getAction($action), $name);
    }


    /**
     * Creates a new route array based on the provided method, route, and callable.
     *
     * @param string $method 
     * @param string $route 
     * @param array  $action
     *
     * @return array Returns an array representing the new route.
     */
    public  function createNewRoute($method, $route, $action, $name = null)
    {

        return  [
            'uri' => $route,
            'method' => $method,
            'controller' => $action['controller'],
            'action' =>  $action['action'],
            'parameters' => $this->extractRouteParameters($route),
            'name' => $name,
        ];
    }

    /**
     * Extracts parameters from the provided route URI pattern.
     * 
     * @param string $route
     * @return array $parameters
     */
    public  function extractRouteParameters(string $route)
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
     * @param string|Closure|array $action 
     * @param string $controller 
     * @return array
     */
    public function getAction($action)
    {
        $callable =   $action[0];
        $controller = $action[1] ?? null;

        if (is_array($action[0])) {
            [$controller, $callable] = $action[0];
        }


        return ['controller' => $controller, 'action' => $callable];
    }


    /**
     * Get all registered routes.
     *
     * @return array All registered routes.
     */
    public function getRoutes()
    {

        return $this->routes;
    }
    /**
     * Get all of the routes keyed by their name.
     *
     * @return array
     */
    public function getRoutesByName()
    {
        return $this->nameList;
    }
    /**
     * Get a route instance by its name.
     *
     * @param  string  $name
     *  
     */
    public function getByName($name)
    {
        return $this->nameList[$name] ?? null;
    }

    /**
     * Loads routes by their names into a separate list.
     */
    public function loadRoutesByNames()
    {
        foreach ($this->getRoutes() as $route => $routeDetails) {
            if (!is_null($routeDetails['name'])) {
                $this->nameList[$routeDetails['name']] = $routeDetails;
            }
        };
    }


    public function setRouteName($route, $routeName)
    {
        $this->routes[$route]['name'] = $routeName;
    }
}
