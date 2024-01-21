<?php

namespace TurFramework\Router;

use Closure;
use TurFramework\Router\Exceptions\RouteException;
use TurFramework\Router\Exceptions\RouteNotFoundException;

class RouteCollection
{

    /**
     * An array containing registered routes.
     *
     * @var array
     */
    private  $routes = [];
    /**
     * route
     *
     * @var string
     */
    protected  $route;
    /**
     * uri
     *
     * @var string
     */
    protected  $uri;
    /**
     * A look-up table of routes by their names.
     */
    protected $nameList = [];


    /**
     * Add a route to the internal routes collection 
     *
     */
    public function addRoute($method, $route, $action, $name = null, $middleware = null)
    {
        $this->routes[$route] = $this->createNewRoute($method, $route, $this->getAction($action), $name, $middleware);
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
    public  function createNewRoute($method, $route, $action, $name = null, $middleware = null)
    {

        return  [
            'uri' => $route,
            'method' => $method,
            'controller' => $action['controller'],
            'action' =>  $action['action'],
            'parameters' => $this->extractRouteParameters($route),
            'name' => $name,
            'middleware' => $middleware,
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
     * Generate a URL for the given route name.
     *
     * @param string $routeName The name of the route.
     * @param array $parameters Optional parameters for the route.
     * @return string The generated URL.
     * @throws RouteException
     */
    public function getRouteByName(string $routeName, array $parameters = []): string
    {

        $route = $this->getByName($routeName);

        if (!$route) {
            throw RouteException::routeNotDefined($routeName);
        }
        $uri = $route['uri'];
        foreach ($route['parameters'] as $key => $parameter) {
            if (!in_array($parameter, array_keys($parameters))) {
                throw RouteException::missingRequiredParameters($routeName, $uri, $parameter);
            }
        }

        foreach ($parameters as $key => $value) {
            $uri = str_replace('{' . $key . '}', $value, $uri);
        }
        return $uri;
    }
    /**
     * Loads routes by their names into a separate list.
     */
    public function refreshNameNameList()
    {
        foreach ($this->getRoutes() as $route => $routeDetails) {
            if (isset($routeDetails['name']) && !is_null($routeDetails['name'])) {
                $this->nameList[$routeDetails['name']] = $routeDetails;
            }
        };
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
    public function match($path, $requestMethod)
    {

        $route = $this->getRoute($path);
        // Check if the route method is not allowed
        if ($this->isRequestMethodNotAllowed($route, $requestMethod)) {
            throw RouteException::requestMethodNotAllowed($requestMethod, $path, $route['method']);
        }
        // Check if no action is associated with the route, throw RouteNotFoundException.
        if (is_null($route)) {
            throw new RouteNotFoundException();
        }

        return $route;
    }

    private function getRoute($path)
    {
        foreach ($this->routes as $key => $route) {

            // Replace route parameters with regex patterns to match dynamic values
            $pattern = preg_replace_callback('/\{(\w+)\}/', function ($matches) {

                return '(?P<' . $matches[1] . '>[^/]+)';
            }, $key);

            $pattern = str_replace('/', '\/', $pattern);

            $pattern = '/^' . $pattern . '$/';

            // Check if the requested path matches the route pattern
            if (preg_match($pattern, $path, $matches)) {

                // Store route parameters and their values
                $route['parameters'] = array_intersect_key($matches, array_flip($route['parameters']));

                return $route;
                break;
            }
        }
        return null;
    }
    private function isRequestMethodNotAllowed($route, $requestMethod)
    {
        return !is_null($route) && $route['method'] !==  $requestMethod;
    }

    public function setMiddleware($middleware)
    {

        $routeMiddleware = is_string($middleware) ? [$middleware] : $middleware;

        $this->routes[array_key_last($this->routes)]['middleware'] = $routeMiddleware;
    }


    public function setRouteName($routeName)
    {
        $this->routes[array_key_last($this->routes)]['name'] = $routeName;
    }
}
