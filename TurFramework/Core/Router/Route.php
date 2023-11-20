<?php

namespace TurFramework\Core\Router;

use TurFramework\Core\Http\Request;
use TurFramework\Core\Http\Response;

class Route
{
    private $routesFiles = [];
    protected static $instance;
    private Response $response;
    private Request $request;

    public static $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public static function addRoute($method, $route, $callable)
    {
        self::$routes[$method][$route] = $callable;
    }

    /**
     * get.
     *
     * @param string route
     * @param array|callable callable
     */
    public static function get(string $route, array|callable $callable)
    {
        self::addRoute(Request::METHOD_GET, $route, $callable);
    }

    public static function controller()
    {
        self::$instance = new static(new Request(),new Response());

        return self::$instance;
    }

    public static function group()
    {
        self::$instance = new static(new Request(),new Response());

        return self::$instance;
    }

    /**
     * post.
     *
     * @param string route
     * @param array|callable callable
     *
     * @return void
     */
    public static function post(string $route, array|callable $callable)
    {
        self::addRoute(Request::METHOD_POST, $route, $callable);
    }

    /**
     * reslove.
     *
     * @return void
     */
    public function reslove()
    {
        if (empty($this->routesFiles)) {
            $this->loadAllRoutesFiles();
        }

        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        $callable = self::$routes[$method][$path] ?? false;

        $this->handleAction($callable);
    }

    /**
     * handleAction.
     *
     * @param mixed action
     *
     * @return void
     */
    private function handleAction($action)
    {
        if (!$action) {
            throw new RouteNotFoundException();
        }

        if (is_callable($action)) {
            call_user_func_array($action, []);
        }

        
        if (is_array($action)) {
            $controllerClass = $action[0];
            $controllerMethod = $action[1];

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
     * load all Routes files.
     */
    public function loadAllRoutesFiles()
    {
        $this->routesFiles = get_all_php_files_in_directory('app/routes');

        if (empty($this->routesFiles)) {
            throw new RouteNotFoundException('no routes files found');
        }

        foreach ($this->routesFiles  as $routeFile) {
            require_once $routeFile;
        }
    }
}
