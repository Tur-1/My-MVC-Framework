<?php

namespace TurFramework\Core\Router;

use Closure;
use ReflectionFunction;
use TurFramework\Core\Router\Exceptions\RouteNotFoundException;
use TurFramework\Core\Router\Exceptions\RouteException;

class Router
{

    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_DELETE = 'DELETE';

    /**
     * The Request object used to handle HTTP requests.
     *
     * @var 
     */
    private  $request;

    /**
     * route
     *
     * @var string
     */
    public  $route;

    /**
     * 
     *
     * @var string
     */
    private $requestMethod;


    /**
     * A look-up table of routes by their names.
     */
    protected $nameList = [];
    /**
     * 
     *
     * @var string
     */
    private $path;

    /**
     * The controller.
     *
     * @var string
     */
    public  $controller;
    /**
     * route params
     *
     * @var array
     */
    public  $routeParams = [];
    /**
     * An array containing registered routes.
     *
     * @var array
     */
    public  $routes = [];

    public function __construct()
    {
    }

    /**
     * Resolve the current request to find and handle the appropriate route.
     * 
     */
    public function resolve($request)
    {
        $this->request =  $request;
        $this->path = $this->request->getPath();
        $this->requestMethod = $this->request->getMethod();

        return RouteResolver::resolve($this->path, $this->requestMethod, $this->routes);
    }

    /**
     * Create a route group 
     *
     * @param callable $callback
     *
     * @return $this
     */
    public  function group(callable $callback)
    {

        $callback();

        return $this;
    }

    /**
     * Create a new instance of the Route class and set the current controller.
     *
     * @param string $controller
     *
     * @return $this
     */
    public function controller(string $controller)
    {
        $this->controller = $controller;
        return  $this;
    }

    /**
     * Register a GET route with the specified route and associated callback.
     *
     * @param string $route 
     * @param string|array|Closure $callable 
     *
     * @return $this
     */
    public function get($route, $callable)
    {
        return $this->addRoute(self::METHOD_GET, $route, $callable);
    }

    /**
     * Register a POST route with the specified route and associated callback.
     *
     * @param string  route the URL pattern for the route
     * @param string|array|Closure $action the callback function or controller action for the route
     *
     * @return  $this;
     */
    public  function post($route,  $action)
    {

        return  $this->addRoute(self::METHOD_POST, $route, $action);
    }

    /**
     * Register a Delete route with the specified route and associated callback.
     *
     * @param string  route the URL pattern for the route
     * @param string|array|Closure $action the callback function or controller action for the route
     *
     * @return  $this;
     */
    public  function delete($route,  $callable)
    {
        return $this->addRoute(self::METHOD_DELETE, $route, $callable);
    }

    /**
     * Add or change the route name.
     *
     * @param string $routeName
     */
    public  function name(string $routeName)
    {
        $this->routes[$this->route]['name'] = $routeName;
    }

    /**
     * Add or change the route name.
     *
     * @param  string  $name
     * 
     * @return array|null
     */
    public function getByName($routeName)
    {
        return $this->nameList[$routeName] ?? null;
    }





    /**
     * Add a route to the internal routes collection for a specific HTTP method.
     *
     * @param string $method 
     * @param string $route 
     * @param string|array|Closure $action 
     * @return  $this;
     */
    private function addRoute($method, $route, $action, $name = null)
    {

        $this->route = $route;
        $this->routes[$route] = $this->createNewRoute($method, $route, $this->getAction($action), $name);

        return  $this;
    }
    /**
     * Creates a new route array based on the provided method, route, and callable.
     *
     * @param string $method   HTTP method (e.g., GET, POST, etc.).
     * @param string $route    URI pattern for the route.
     * @param array  $callable Array containing controller and action information.
     *
     * @return array Returns an array representing the new route.
     */
    private function createNewRoute($method, $route, $action, $name = null)
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
    private  function extractRouteParameters(string $route)
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
    private  function getAction($action)
    {
        if (!is_null($this->controller) && is_string($action)) {
            return ['controller' =>  $this->controller, 'action' =>  $action];
        }

        if (is_array($action)) {
            return ['controller' =>  $action[0], 'action' =>  $action[1]];
        }

        if (is_callable($action)) {
            return ['controller' => null, 'action' => $action];
        }
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
     * Get all registered routes by names.
     *
     * @return array All registered routes.
     */
    public function getNameList()
    {
        return $this->nameList;
    }

    public function setRouteParams($routeParams)
    {
        $this->routeParams = $routeParams;
    }
    /**
     * Loads routes.
     * If cached file exists, loads from cache, otherwise loads route files and creates a cache file.
     * @return $this
     */
    public function loadRotues()
    {

        $this->routes = (new RouteFileRegistrar($this))->loadRotues();

        $this->loadRoutesByNames();


        return $this;
    }


    /**
     * Loads routes by their names into a separate list.
     */
    private function loadRoutesByNames()
    {
        foreach ($this->routes as $route => $routeDetails) {
            if (!is_null($routeDetails['name'])) {
                $this->nameList[$routeDetails['name']] = $routeDetails;
            }
        };
    }
}
