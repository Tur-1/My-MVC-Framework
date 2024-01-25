<?php

namespace TurFramework\Router;

use TurFramework\Support\Arr;
use TurFramework\Router\Exceptions\RouteException;

class RouteUrlGenerator
{



    /**
     * Generate a URL for the given route.
     *
     * @param  \TurFramework\Router\Route\ $route
     * @param  array  $parameters 
     * @return string
     *
     * @throws RouteException
     */
    public function generate($route, $parameters = [])
    {

        $this->validateRequiredParameters($parameters, $route);

        $path =  $this->replaceNamedParameters($route['uri'], $parameters);

        return $path;
    }

    /**
     * check if there is Missing Required Parameter
     * @param array $parameters
     * @throws RouteException
     */
    protected  function validateRequiredParameters($parameters, $route)
    {
        foreach ($route['parameters'] as $parameter) {
            if (!in_array($parameter, array_keys($parameters)) && !str_ends_with($parameter, '?')) {
                throw RouteException::missingRequiredParameters($route['name'], $route['uri'], $parameter);
            }
        }
    }
    /**
     * Replace all of the named parameters in the path.
     *
     * @param  string  $path
     * @param  array  $parameters
     * @return string
     */
    protected  function replaceNamedParameters($path, $parameters)
    {
        $uri = preg_replace_callback('/\{(.*?)(\?)?\}/', function ($match) use ($parameters) {
            if (isset($parameters[$match[1]]) && $parameters[$match[1]] !== '') {
                return Arr::pull($parameters, $match[1]);
            }
        }, $path);


        return '/' . trim(preg_replace('/\{.*?\?\}/', '', $uri), '/');
    }
}
