<?php

namespace TurFramework\Core\Facades;

use TurFramework\Core\Router\Router;
use TurFramework\Core\Http\Request;
use TurFramework\Core\Http\Response;

/**
 * @method static TurFramework\Core\Router\Router get(string $uri, array|string|callable|null $action = null)
 * @method static TurFramework\Core\Router\Router post(string $uri, array|string|callable|null $action = null)
 * @method static TurFramework\Core\Router\Router delete(string $uri, array|string|callable|null $action = null)
 * @method static TurFramework\Core\Router\Router group($callback) 
 * @method static TurFramework\Core\Router\Router controller($controller) 
 * @method static TurFramework\Core\Router\Router name($name) 
 * @method  TurFramework\Core\Router\Router getRoutes() 
 * @method  TurFramework\Core\Router\Router loadAllRoutesFiles() 
 * @method  TurFramework\Core\Router\Router resolve() 
 * 
 * 
 * @see TurFramework\Core\Router\Router
 */
class Route
{
    protected static $routerInstance;

    public static function setFacadeRouter($request, $response)
    {
        static::$routerInstance = new Router($request, $response);
    }

    public static function __callStatic($method, $args)
    {
        $router = static::$routerInstance ?: static::$routerInstance = new Router(new Request(), new Response());

        return call_user_func_array([$router, $method], $args);
    }

    public function __call($method, $args)
    {
        $router = static::$routerInstance ?: static::$routerInstance = new Router(new Request(), new Response());

        return call_user_func_array([$router, $method], $args);
    }
}
