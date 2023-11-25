<?php

namespace TurFramework\Core\Router;

use Closure;
use TurFramework\Core\Http\Request;
use TurFramework\Core\Http\Response;
use TurFramework\Core\Exceptions\BadMethodCallException;

class Router implements RouteInterface
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
     * 
     *
     * @var string
     */
    private $requestMethod;
    private static $instance;
    /**
     * 
     *
     * @var string
     */
    private static $name;
    /**
     * 
     *
     * @var string
     */
    private $path;
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
    private static $lastAddedRoute = null;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Resolve the current request to find and handle the appropriate route.
     *
     * @return void
     */
    public function resolve()
    {


        $this->path = $this->request->getPath();
        $this->requestMethod = $this->request->getMethod();

        // Retrieve the callable associated with the requested method and path, if exists.
        $route = $this->matchRoute($this->path);

        $this->handleRoute($route);
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
        return  $instance;
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
        $instance = new self(new Request(), new Response());
        self::addRoute(Request::METHOD_GET, $route, $callable);
        return  $instance;
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
        $instance = new self(new Request(), new Response());
        self::addRoute(Request::METHOD_POST, $route, $callable);
        return  $instance;
    }

    /**
     * Register a Delete route with the specified route and associated callback.
     *
     * @param string  route the URL pattern for the route
     * @param string|array|Closure $callable the callback function or controller action for the route
     *
     * @return void
     */
    public static function delete($route,  $callable)
    {
        self::addRoute(Request::METHOD_DELETE, $route, $callable);
    }

    /**
     * Add or change the route name.
     *
     * @param  string  $name
     * 
     */
    public static function name($routeName)
    {
        // This method will set a name for the last added route

        if (!is_null(self::$lastAddedRoute) && isset(self::$routes[self::$lastAddedRoute])) {
            self::$routes[self::$lastAddedRoute]['name'] = $routeName;
        }
        return new self(new Request(), new Response());
    }

    /**
     * Retrieve the route handler associated with the given method and path.
     *
     * @param string $method The HTTP method of the route (e.g., GET, POST).
     * @param string $path The URL path of the route.
     * @return mixed|false The route handler associated with the route or false if not found.
     */
    public function matchRoute($path)
    {
        $handler = null;


        foreach (self::$routes as $route => $routeDetails) {

            // Replace route parameters with regex patterns to match dynamic values
            $pattern = preg_replace_callback('/\{(\w+)\}/', function ($matches) {

                return '(?P<' . $matches[1] . '>[^/]+)';
            }, $route);

            $pattern = str_replace('/', '\/', $pattern);

            $pattern = '/^' . $pattern . '$/';

            // Check if the requested path matches the route pattern
            if (preg_match($pattern, $path, $matches)) {

                $handler = $routeDetails;

                // Store route parameters and their values
                $handler['parameters'] = array_intersect_key($matches, array_flip($handler['parameters']));

                break;
            }
        }


        return $handler;
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
            self::$routes[] = require_once $routeFile;
        }
        // $routesCacheFile = base_path('bootstrap/cache/routes.php');

        // if (file_exists($routesCacheFile)) {

        //     self::$routes = require_once $routesCacheFile;
        // } else {


        //     $this->routesFiles = get_all_php_files_in_directory('app/routes');

        //     if (empty($this->routesFiles)) {
        //         throw new RouteNotFoundException('No route files found');
        //     }

        //     foreach ($this->routesFiles as $routeFile) {
        //         self::$routes[] = require_once $routeFile;
        //     }


        //     // After loading, create a cache file for subsequent requests
        //     $this->cacheRoutes($routesCacheFile);
        // }
    }

    /**
     * Add a route to the internal routes collection for a specific HTTP method.
     *
     * @param string $method The HTTP method (GET, POST, etc.) for the route.
     * @param string $route The URL pattern for the route.
     * @param string|array|Closure $callable The callback function or controller action for the route.
     * @return void
     */
    private static function addRoute($method, $route, $callable, $name = null)
    {
        self::$lastAddedRoute = $route; // Set the last added route
        self::$routes[$route] = self::createNewRoute($method, $route, self::getCallable($callable), $name);
        return new self(new Request(), new Response()); // Return the Router object for method chaining
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
    private static function createNewRoute($method, $route, $callable, $name = null)
    {

        return  [
            'uri' => $route,
            'method' => $method,
            'controller' => $callable['controller'],
            'action' =>  $callable['action'],
            'parameters' => self::extractParametersFromRoute($route),
            'name' => $name,
        ];
    }

    /**
     * Extracts parameters from the provided route URI pattern.
     *
     * @param string $route URI pattern for the route.
     *
     * @return array Returns an array containing the extracted parameters.
     */
    private static function extractParametersFromRoute($route)
    {
        $parameters = [];
        $routeParts = explode('/', $route);

        foreach ($routeParts as $part) {
            // Checks if the part of the route is a parameter placeholder in the form of {param}
            if (strpos($part, '{') === 0 && strpos($part, '}') === strlen($part) - 1) {
                $parameters[] = substr($part, 1, -1); // Extracts the parameter name without braces
            }
        }

        return  $parameters; // Returns an array of extracted parameters
    }

    /**
     * Determines the callable format based on the input and returns it as an array.
     *
     * @param mixed $callable Callable associated with the route.
     *
     * @return array|null Returns an array containing controller and action, or null if the format is not recognized.
     */
    private static function getCallable($callable)
    {
        if (!is_null(self::$controller) && is_string($callable)) {
            return ['controller' =>  self::$controller, 'action' =>  $callable];
        }

        if (is_array($callable)) {
            return ['controller' =>  $callable[0], 'action' =>  $callable[1]];
        }

        if (is_callable($callable)) {
            return ['controller' => null, 'action' => $callable];
        }
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
    private function handleRoute($route)
    {

        if ($this->isMethodNotAllowedForRoute($route)) {

            throw new MethodNotAllowedHttpException($this->requestMethod, $this->path, $route['method']);
        }

        // Check if no action is associated with the route, throw RouteNotFoundException.
        if (is_null($route)) {
            throw new RouteNotFoundException();
        }

        // If the action is a callable function, execute it.
        if (is_callable($route['action'])) {
            $this->invokeControllerMethod($route['action'], $route['parameters']);
            return;
        }


        $controllerClass = $route['controller'];
        $controllerMethod = $route['action'];

        if ($this->isControllerNotExists($controllerClass)) {
            throw new ControllerNotFoundException("Target class [$controllerClass] does not exist");
        }

        $controller = new $controllerClass();

        if ($this->isMethodNotExistsInController($controller, $controllerMethod)) {
            throw new \BadMethodCallException("Method  $controllerClass::$controllerMethod  does not exist!");
        }

        $this->invokeControllerMethod([$controller, $controllerMethod], $route['parameters']);
    }

    private function invokeControllerMethod($callable, $parameters)
    {

        return  call_user_func_array($callable,  [$this->request, ...$parameters]);
    }
    // Method to check if the requested method matches the route method
    private function isMethodNotAllowedForRoute($route)
    {

        if (!is_null($route)  && $route['method'] !== $this->requestMethod) {
            return true;
        }

        return false;
    }


    private function isControllerNotExists($controllerClass)
    {
        return !is_null($controllerClass) && !class_exists($controllerClass);
    }

    private function isMethodNotExistsInController($controller, $methodName)
    {
        return !method_exists($controller, $methodName);
    }


    private function cacheRoutes($cacheFile)
    {
        // Dump the routes into a cache file
        $routes = $this->getRoutes(); // Assuming $this->routesFiles contains loaded routes
        file_put_contents($cacheFile, '<?php return ' . var_export($routes, true) . ';');
    }
}
