<?php

namespace TurFramework\Core\Router;

use TurFramework\Core\Facades\Request;

class RouteRegistrar
{


    /**
     * route key
     *
     * @var array
     */
    public  $route;

    public function __construct()
    {
        $this->route = new Router(new Request());
    }

    /**
     * The currently active controller.
     *
     * @var mixed
     */
    public  $controller;

    /**
     * Create a route group 
     *
     * @param callable $callback
     *
     * @return $this
     */
    public  function group(callable $callback)
    {

        $callback();

        return $this;
    }

    /**
     * Create a new instance of the Route class and set the current controller.
     *
     * @param string $controller the name of the controller
     *
     * @return $this
     */
    public function controller(string $controller)
    {
        $this->controller = $controller;
        return  $this;
    }

    /**
     * Register a GET route with the specified route and associated callback.
     *
     * @param string $route the URL pattern for the route
     * @param string|array|Closure $callable the callback function or controller action for the route
     *
     *@return  $this;
     */
    public function get($route, $callable)
    {

        return $this->addRoute(Request::METHOD_GET, $route, $callable);
    }

    /**
     * Register a POST route with the specified route and associated callback.
     *
     * @param string  route the URL pattern for the route
     * @param string|array|Closure $callable the callback function or controller action for the route
     *
     * @return  $this;
     */
    public  function post($route,  $callable)
    {

        return  $this->addRoute(Request::METHOD_POST, $route, $callable);
    }

    /**
     * Register a Delete route with the specified route and associated callback.
     *
     * @param string  route the URL pattern for the route
     * @param string|array|Closure $callable the callback function or controller action for the route
     *
     * @return  $this;
     */
    public  function delete($route,  $callable)
    {
        return $this->addRoute(Request::METHOD_DELETE, $route, $callable);
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

        return $this;
    }
}
