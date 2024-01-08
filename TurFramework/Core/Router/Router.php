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
     * The singleton instance of the router.
     *
     * @var self|null
     */
    protected static $instance;



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


    private $action = [];

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
     * @var 
     */
    public  $routes;


    public function __construct()
    {
        $this->routes = new RouteCollection();
    }


    /**
     * Get the globally available instance of the container.
     *
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
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

        return RouteResolver::resolve($this->path, $this->requestMethod, $this->routes->getRoutes());
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
        $this->action[1] = $controller;
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
        $this->routes->setRouteName($this->route, $routeName);
    }

    /**
     * Generates a URL based on the route name and parameters.
     *
     * @param string $routeName The name of the route
     * @param array $parameters (Optional) Parameters for the route
     * @return string The generated URL
     * @throws RouteException When the route is not defined
     * @throws RouteException When required parameters are missing
     */
    public function route($routeName, $parameters = [])
    {

        $route = $this->routes->getByName($routeName);

        if (is_null($route)) {
            throw RouteException::routeNotDefined($routeName);
        }
        foreach ($route['parameters'] as $key => $parameter) {
            if (!in_array($parameter, array_keys($parameter))) {
                throw RouteException::invalidArgument($routeName, $route['uri'], $parameter);
            }
        }

        $url = $route['uri'];
        foreach ($parameters as $key => $parameter) {
            $url = str_replace('{' . $key . '}', $parameter, $url);
        }
        return $url;
    }
    /**
     * Add a route to the internal routes collection .
     *
     * @param string $method 
     * @param string $route 
     * @param string|array|Closure $action 
     * @return  $this;
     */
    private function addRoute($method, $route, $action, $name = null)
    {


        $this->route = $route;
        $this->action[0] = $action;

        $this->routes->addRoute($method, $route, $this->action, $name);
        return  $this;
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

        (new RouteFileRegistrar($this))->loadRotues();
        $this->routes->loadRoutesByNames();

        return $this;
    }
}
