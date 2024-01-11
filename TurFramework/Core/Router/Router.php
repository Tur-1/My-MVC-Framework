<?php

namespace TurFramework\Core\Router;

use Closure;
use TurFramework\Core\Router\Route;

class Router
{
    // HTTP request methods
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_DELETE = 'DELETE';

    /**
     * The route object responsible for managing registered routes.
     *
     * @var Route
     */
    protected $route;

    /**
     * An array to store action details, such as controller information.
     *
     * @var array
     */
    private $action = [];

    /**
     * The controller associated with the route.
     *
     * @var string
     */
    protected  $controller;

    /**
     * Router constructor.
     * Initializes the router with a new Route instance.
     */
    public function __construct()
    {
        $this->route = new Route();
    }

    /**
     * Resolve the current request to find and handle the appropriate route.
     * 
     */
    public function resolve($request)
    {
        return RouteResolver::resolve(
            $request->getPath(),
            $request->getMethod(),
            $this->route->routes
        );
    }

    /**
     * Create a route group 
     *
     * @param Closure|string $routes Closure function or routes path to load routes
     *
     * @return $this
     */
    public  function group($routes)
    {

        if ($routes instanceof Closure) {
            $routes();
        } else {

            (new RouteFileRegistrar($this))->register($routes);
            $this->route->loadRoutesByNames();
        }


        return $this;
    }

    /**
     * Set the controller for the route.
     *
     * @param string $controller 
     * @return  $this
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
     * @param string|array|Closure $action 
     *
     * @return $this
     */
    public function get($route, $action)
    {
        return $this->addRoute(self::METHOD_GET, $route, $action);
    }

    /**
     * Register a POST route with the specified route and associated callback.
     *
     * @param string  $route 
     * @param string|array|Closure $action
     *
     * @return  $this;
     */
    public  function post($route, $action)
    {

        return  $this->addRoute(self::METHOD_POST, $route, $action);
    }

    /**
     * Register a Delete route with the specified route and associated callback.
     *
     * @param string  $route
     * @param string|array|Closure $action
     *
     * @return  $this;
     */
    public  function delete($route, $action)
    {
        return $this->addRoute(self::METHOD_DELETE, $route, $action);
    }

    /**
     * Add or change the route name.
     *
     * @param string $routeName
     */
    public  function name(string $routeName)
    {
        $this->route->setRouteName($routeName);
    }

    public function getRouteByName($routeName, $params)
    {
        return $this->route->route($routeName, $params);
    }


    /**
     * Add a route to the internal routes collection.
     *
     * @param string $method The HTTP method of the route.
     * @param string $route The route path.
     * @param string|array|Closure $action The callback or controller action associated with the route.
     * @param string|null $name The name of the route.
     * @return  $this;
     */
    private function addRoute($method, $route, $action, $name = null)
    {
        $this->action[0] = $action;
        $this->route->addRoute($method, $route, $this->action, $name);
        return $this;
    }
}
