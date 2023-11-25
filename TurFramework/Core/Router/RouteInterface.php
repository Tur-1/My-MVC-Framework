<?php

namespace TurFramework\Core\Router;

interface RouteInterface
{
    public function resolve();

    /**
     * Create a route group 
     *
     * @param \Closure|array|string $callback
     *
     * @return $instance
     */
    public static function group($callback);
    /**
     * Create a new instance of the Route class and set the current controller.
     *
     * @param string $controller the name of the controller
     *
     * @return Route an instance of the Route class with the current controller set
     */
    public static function controller($controller);
    /**
     * Register a GET route with the specified route and associated callback.
     *
     * @param string $route the URL pattern for the route
     * @param string|array|Closure $callable the callback function or controller action for the route
     *
     * @return void
     */
    public static function get($route, $callable);

    /**
     * Register a POST route with the specified route and associated callback.
     *
     * @param string  route the URL pattern for the route
     * @param string|array|Closure $callable the callback function or controller action for the route
     *
     * @return void
     */
    public static function post($route,  $callable);

    /**
     * Register a Delete route with the specified route and associated callback.
     *
     * @param string  route the URL pattern for the route
     * @param string|array|Closure $callable the callback function or controller action for the route
     *
     * @return void
     */
    public static function delete($route,  $callable);
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

    /**
     * Get all registered routes.
     *
     * @return array All registered routes.
     */
    public function getRoutes();
    /**
     * Load all route files from the 'app/routes' directory.
     *
     * @return void
     *
     * @throws RouteNotFoundException If no routes files are found.
     */
    public function loadAllRoutesFiles();
}
