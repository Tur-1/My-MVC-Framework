<?php

namespace TurFramework\Core\Router;

use Closure;
use TurFramework\Core\Http\Request;
use TurFramework\Core\Http\Response;
use TurFramework\Core\Exceptions\BadMethodCallException;

class Route
{
    /**
     * The Response object used to handle HTTP responses.
     *
     * @var Response
     */
    private Response $response;

    /**
     * The Request object used to handle HTTP requests.
     *
     * @var Request
     */
    private Request $request;

    /**
     * An array containing HTTP verbs for reference.
     *
     * @var array
     */
    public static $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    /**
     * An array to store route files loaded for caching.
     *
     * @var array
     */
    private $routesFiles = [];

    /**
     * The currently active controller.
     *
     * @var mixed
     */
    public static $controller;

    /**
     * An array containing registered routes.
     *
     * @var array
     */
    public static $routes = [];


    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Create a route group 
     *
     * @param \Closure|array|string $callback
     *
     * @return $instance
     */
    public static function group($callback)
    {
        $instance = new self(new Request(), new Response());

        $callback();

        return $instance;
    }

    /**
     * Create a new instance of the Route class and set the current controller.
     *
     * @param string $controller the name of the controller
     *
     * @return Route an instance of the Route class with the current controller set
     */
    public static function controller($controller)
    {
        $instance = new self(new Request(), new Response());
        $instance::$controller = $controller;

        return $instance;
    }

    /**
     * Register a GET route with the specified route and associated callback.
     *
     * @param string $route the URL pattern for the route
     * @param string|array|Closure $callable the callback function or controller action for the route
     *
     * @return void
     */
    public static function get($route, $callable)
    {
        self::addRoute(Request::METHOD_GET, $route, $callable);
    }

    /**
     * Register a POST route with the specified route and associated callback.
     *
     * @param string  route the URL pattern for the route
     * @param string|array|Closure $callable the callback function or controller action for the route
     *
     * @return void
     */
    public static function post($route,  $callable)
    {
        self::addRoute(Request::METHOD_POST, $route, $callable);
    }

    /**
     * Add a route to the internal routes collection for a specific HTTP method.
     *
     * @param string $method The HTTP method (GET, POST, etc.) for the route.
     * @param string $route The URL pattern for the route.
     * @param string|array|Closure $callable The callback function or controller action for the route.
     * @return void
     */
    private static function addRoute($method, $route, $callable)
    {

        if (!is_null(self::$controller) && is_string($callable)) {
            self::$routes[$method][$route] =  [self::$controller, $callable];
        } else {

            self::$routes[$method][$route] = $callable;
        }
    }


    /**
     * Resolve the current request to find and handle the appropriate route.
     *
     * @return void
     */
    public function resolve()
    {
        // Check if routes files are not loaded, then load them.
        if (empty($this->routesFiles)) {
            $this->loadAllRoutesFiles();
        }


        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        // Retrieve the callable associated with the requested method and path, if exists.
        $callable = self::$routes[$method][$path] ?? false;

        $this->handleAction($callable);
    }

    /**
     * Handle the resolved action (callable or controller method) based on the route.
     *
     * @param mixed $action The action associated with the resolved route.
     * @return void
     *
     * @throws RouteNotFoundException If no action is found for the route.
     * @throws ControllerNotFoundException If the specified controller class does not exist.
     * @throws \BadMethodCallException If the controller method does not exist.
     */
    private function handleAction($action)
    {
        // Check if no action is associated with the route, throw RouteNotFoundException.
        if (!$action) {
            throw new RouteNotFoundException();
        }

        // If the action is a callable function, execute it.
        if (is_callable($action)) {
            call_user_func_array($action, []);
        }


        if (is_string($action)) {
            throw new \BadMethodCallException("Invalid route action: [ $action ].");
        }
        // If the action is an array (controller class and method).
        if (is_array($action)) {
            $controllerClass = $action[0];
            $controllerMethod = $action[1];


            // Check if the specified controller class exists.
            if (!class_exists($controllerClass)) {
                throw new ControllerNotFoundException("Target class [$controllerClass] does not exist");
            }


            $controller = new $controllerClass();


            if (!method_exists($controller, $controllerMethod)) {
                throw new \BadMethodCallException("Method  $controllerClass::$controllerMethod  does not exist!");
            }


            call_user_func_array([$controller, $controllerMethod], [$this->request]);
        }
    }


    /**
     * Get all registered routes.
     *
     * @return array All registered routes.
     */
    public function getRoutes()
    {

        return self::$routes;
    }


    /**
     * Load all route files from the 'app/routes' directory.
     *
     * @return void
     *
     * @throws RouteNotFoundException If no routes files are found.
     */
    public function loadAllRoutesFiles()
    {

        $this->routesFiles = get_all_php_files_in_directory('app/routes');


        if (empty($this->routesFiles)) {
            throw new RouteNotFoundException('No route files found');
        }


        foreach ($this->routesFiles as $routeFile) {
            require_once $routeFile;
        }
    }
}
