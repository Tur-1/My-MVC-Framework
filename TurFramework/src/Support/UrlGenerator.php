<?php

namespace TurFramework\Support;

use TurFramework\Http\Request;
use TurFramework\Router\RouteCollection;
use TurFramework\Router\RouteUrlGenerator;
use TurFramework\Router\Exceptions\RouteException;

class UrlGenerator
{
    /**
     * The route collection.
     *
     * @var \TurFramework\Router\RouteCollection
     */
    protected $routes;

    /**
     * The request instance.
     *
     * @var \TurFramework\Http\Request
     */
    protected $request;

    /**
     * Create a new URL Generator instance.
     *
     * @param  \TurFramework\Router\RouteCollection  $routes
     * @param  \TurFramework\Http\Request $request
     * @return void
     */
    public function __construct(RouteCollection $routes, Request $request)
    {
        $this->routes = $routes;

        $this->request = $request;
    }

    public function asset($path)
    {
        return $path;
    }
    /**
     * Determine if the given path is a valid URL.
     *
     * @param  string  $path
     * @return bool
     */
    public function isValidUrl($path)
    {
        if (!preg_match('~^(#|//|https?://|(mailto|tel|sms):)~', $path)) {
            return filter_var($path, FILTER_VALIDATE_URL) !== false;
        }

        return true;
    }

    /**
     * Get the full URL for the current request.
     *
     * @return string
     */
    public function full()
    {
        return $this->request->fullUrl();
    }
    /**
     * Get the URL to a named route.
     *
     * @param  string  $name
     * @param  mixed  $parameters
     * @return string
     *
     * @throws \TurFramework\Router\RouteException
     */
    public function route($name, $parameters = [])
    {

        $route = $this->routes->getByName($name);

        if (!$route) {
            throw RouteException::routeNotDefined($name);
        }

        return (new RouteUrlGenerator())->generate($route, $parameters);
    }
}
