<?php

namespace src\Router;

use src\Http\Request;
use src\Http\Response;
use src\Router\Exception\RouteException;

class Route implements RouteInterface
{
    private const METHOD_POST = 'post';
    private const METHOD_GET = 'get';

    private Request $request;
    private Response $response;

    public static $routes = array();




    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
    }
    public static function addRoute($method, $route, $action)
    {
        self::$routes[$method][$route] = $action;
    }

    public static function get($route, $action)
    {
        self::addRoute(self::METHOD_GET, $route, $action);
    }
    public static function post($route, $action)
    {
        self::addRoute(self::METHOD_POST, $route, $action);
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
            throw new RouteException(message: 'page not found');
        }

        if (is_callable($action)) {
            call_user_func_array($action, []);
        }

        if (is_array($action)) {
            $controller = new $action[0];
            $controllerMethod = $action[1];

            call_user_func_array([$controller, $controllerMethod], []);
        }
    }
}
