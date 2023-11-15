<?php

namespace TurFramework\src\Router;

use TurFramework\src\Http\Request;
use TurFramework\src\Http\Response;
use TurFramework\src\Router\Exception\RouteNotFoundException;

class Route
{
    private Response $response;
    private Request $request;

    private static $_instance = null;
    public static $routes = [];
    public $routesFiles = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    // public static function getInstance()
    // {
    //     if (self::$_instance === null) {
    //         self::$_instance = new self(new Request(), new Response());
    //     }

    //     return self::$_instance;
    // }

    public static function addRoute($method, $route, $action)
    {
        self::$routes[$method][$route] = $action;
    }

    public static function get($route, $action)
    {
        self::addRoute(Request::METHOD_GET, $route, $action);
    }

    public static function post($route, $action)
    {
        self::addRoute(Request::METHOD_POST, $route, $action);
    }

    public function reslove()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        $action = self::$routes[$method][$path] ?? false;

        $this->handleAction($action);
    }

    private function handleAction($action)
    {
        if (!$action) {
            throw new RouteNotFoundException('page not found');
        }

        if (is_callable($action)) {
            call_user_func_array($action, []);
        }

        if (is_array($action)) {
            $controller = new $action[0]();
            $controllerMethod = $action[1];

            call_user_func_array([$controller, $controllerMethod], []);
        }
    }
}
