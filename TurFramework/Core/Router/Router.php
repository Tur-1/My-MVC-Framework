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
    public  $routes = [];

    public $routeCollection;

    public function __construct()
    {
        $this->routeCollection = new RouteCollection();
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

        return RouteResolver::resolve($this->path, $this->requestMethod, $this->routes);
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

            require base_path($routes);
        }


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
        $this->routeCollection->setRouteName($this->route, $routeName);
    }

    public function loadRotues()
    {

        require base_path('app/routes/web.php');

        return $this;
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
        $this->routes[$route] = $this->routeCollection->addRoute($method, $route, $this->action, $name);
        return  $this;
    }


    public function setRouteParams($routeParams)
    {
        $this->routeParams = $routeParams;
    }
}
