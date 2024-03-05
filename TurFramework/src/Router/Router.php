<?php

namespace TurFramework\Router;

use Closure;
use TurFramework\Router\Route;
use TurFramework\Router\RouteCollection;
use TurFramework\Router\MiddlewareResolver;
use TurFramework\Router\RouteFileRegistrar;

class Router
{

    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_DELETE = 'DELETE';
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [];


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
     * Register a short-hand name for a middleware.
     *
     * @param  string  $name
     * @param  string  $class
     * @return $this
     */
    public function setRouteMiddleware($name, $class)
    {
        $this->routeMiddleware[$name] = $class;
    }
    public function setGlobalMiddleware($middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * Loads specific route files provided in the array.
     *
     * @param array $routes
     * @return void
     */
    public function load(array $routes)
    {
        $routeFiles = new RouteFileRegistrar($this);

        $routeFiles->load($routes);


        $this->routes->refreshNameList();
    }

    /**
     * Loads all routes from Routes directory.
     *
     * @return void
     */
    public function loadAllRoutes()
    {
        $routeFiles = new RouteFileRegistrar($this);

        $routeFiles->loadAllRoutes(app_path('Routes'));

        $this->routes->refreshNameList();
    }
    /**
     * Resolve the current request to find and handle route.
     * 
     */
    public function resolve($request)
    {
        $route = $this->routes->match($request->getPath(), $request->getMethod());

        MiddlewareResolver::handle($this->middleware, $this->routeMiddleware, $route, $request);

        Route::resolve($request, $route);
    }

    /**
     * Create a route group 
     *
     * @param Closure $routes
     * @return $this
     */
    public  function group(Closure $routes)
    {

        $routes();

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
     * Register a GET route
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
     * Register a POST route
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
     * Register a Delete route
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
}
