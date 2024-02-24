<?php

namespace TurFramework\Router;

use Closure;
use App\Http\Kernel;
use TurFramework\Router\Route;
use TurFramework\Router\RouteCollection;
use TurFramework\Router\RouteFileRegistrar;

class Router
{

    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_DELETE = 'DELETE';

    /**
     * The route collection instance.
     *
     * @var RouteCollection
     */
    protected $routes;

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
        $this->routes = new RouteCollection();
    }

    /**
     * Resolve the current request to find and handle the appropriate route.
     * 
     */
    public function resolve($request)
    {
        $route = $this->routes->match($request->getPath(), $request->getMethod());

        Route::resolve($request, $route);
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

        $routes();

        return $this;
    }

    public function load($routes)
    {
        $routeFiles = new RouteFileRegistrar($this);

        $routeFiles->load($routes);

        $this->routes->refreshNameNameList();
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
        return $this->addRoute(self::METHOD_POST, $route, $action);
    }
    /**
     * Add a route to the internal routes collection.
     *
     * @param string $method 
     * @param string $path  
     * @param string|array|Closure $action 
     * @param string|null $name 
     * @return  $this;
     */
    private function addRoute($method, $path, $action, $name = null)
    {

        $this->action[0] = $action;

        $this->routes->addRoute($method, $path, $this->action, $name);
        return $this;
    }
    /**
     * Add or change the route name.
     *
     * @param string $routeName
     */
    public  function name(string $routeName)
    {
        $this->routes->setRouteName($routeName);

        return $this;
    }

    public function getRouteCollection()
    {
        return $this->routes;
    }
    public function middleware($middleware)
    {
        $this->routes->setMiddleware($middleware);

        return $this;
    }
}
