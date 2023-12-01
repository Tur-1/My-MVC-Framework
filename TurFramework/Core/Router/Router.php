<?php

namespace TurFramework\Core\Router;

use Error;
use Closure;
use TypeError;
use ErrorException;
use InvalidArgumentException;
use function PHPSTORM_META\type;
use TurFramework\Core\Http\Request;

use TurFramework\Core\Http\Response;
use TurFramework\Core\Exceptions\BadMethodCallException;

class Router implements RouteInterface
{

    private static $instance;

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
     * route key
     *
     * @var array
     */
    public static $route;

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


    /**
     * A look-up table of routes by their names.
     *
     * @var \Illuminate\Routing\Route[]
     */
    protected $nameList = [];
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
    public  $controller;

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
        self::$instance = $this;
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

        //  match the incoming URL to the defined routes 
        $route = $this->matchRoute($this->path);



        $this->handleRoute($route);
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

        call_user_func($callback);
        return self::$instance;
    }

    /**
     * Create a new instance of the Route class and set the current controller.
     *
     * @param string $controller the name of the controller
     *
     * @return Route an instance of the Route class with the current controller set
     */
    public static function controller(string $controller)
    {

        self::$instance->controller = $controller;
        return  self::$instance;
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

        return self::addRoute(Request::METHOD_GET, $route, $callable);
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

        return  self::addRoute(Request::METHOD_POST, $route, $callable);
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
    public  function name($routeName)
    {

        $this->setRouteName($routeName);

        return self::$instance;
    }
    public function getByName($routeName)
    {
        return $this->nameList[$routeName];
    }

    private function setRouteName($routeName)
    {
        return self::$routes[self::$route]['name'] = $routeName;
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

            if (!is_null($routeDetails['name'])) {
                $this->nameList[$routeDetails['name']] = $routeDetails;
            }
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
        $this->routesFiles = get_files_in_directory('app/routes');

        if (empty($this->routesFiles)) {
            throw new RouteNotFoundException('No route files found');
        }

        foreach ($this->routesFiles as $routeFile) {
            if (!is_int($routeFile)) {
                self::$routes[] = require_once $routeFile;
            }
        }

        foreach (self::$routes as $route => $routeDetails) {
            if (!is_int($routeDetails) && !is_null($routeDetails['name'])) {
                $this->nameList[$routeDetails['name']] = $routeDetails;
            }
        }
    }

    private function loadRotues()
    {
        $routesCacheFile = base_path('bootstrap/cache/routes.php');

        if (file_exists($routesCacheFile)) {

            self::$routes = require_once $routesCacheFile;
        } else {


            $this->routesFiles = get_files_in_directory('app/routes');

            if (empty($this->routesFiles)) {
                throw new RouteNotFoundException('No route files found');
            }

            foreach ($this->routesFiles as $routeFile) {
                self::$routes[] = require_once $routeFile;
            }


            // After loading, create a cache file for subsequent requests
            $this->cacheRoutes($routesCacheFile);
        }
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

        self::$route = $route;

        self::$routes[$route] = self::createNewRoute($method, $route, self::getCallable($callable), $name);

        return  self::$instance;
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
        if (!is_null(self::$instance->controller) && is_string($callable)) {
            return ['controller' =>  self::$instance->controller, 'action' =>  $callable];
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

        // Check if the route method is not allowed
        if ($this->isMethodNotAllowedForRoute($route)) {
            throw new MethodNotAllowedHttpException($this->requestMethod, $this->path, $route['method']);
        }

        // Check if no action is associated with the route, throw RouteNotFoundException.
        if (is_null($route)) {
            throw new RouteNotFoundException();
        }

        // If the action is a callable function, execute it
        if (is_callable($route['action'])) {
            $this->invokeControllerMethod($route['action'], $route['parameters']);
            return;
        }

        // Extract controller class and method from the route
        $controllerClass = $route['controller'];
        $controllerMethod = $route['action'];


        // Check if the controller class exists
        if ($this->isControllerNotExists($controllerClass)) {
            throw new ControllerNotFoundException("Target class [$controllerClass] does not exist");
        }

        $controller = new $controllerClass();

        // Check if the method exists in the controller
        if ($this->isMethodNotExistsInController($controller, $controllerMethod)) {
            throw new \BadMethodCallException("Method  $controllerClass::$controllerMethod  does not exist!");
        }

        // Invoke the controller method
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
