<?php

namespace TurFramework\Core\Facades;

use TurFramework\Core\Router\Router;
use TurFramework\Core\Facades\Request;

/**
 * @method static TurFramework\Core\Router\Router get(string $uri, string|array|Closure $action = null)
 * @method static TurFramework\Core\Router\Router post(string $uri, array|string|callable|null $action = null)
 * @method static TurFramework\Core\Router\Router delete(string $uri, array|string|callable|null $action = null)
 * @method static TurFramework\Core\Router\Router controller($controller) 
 * @method  TurFramework\Core\Router\Router group($callback) 
 * @method  TurFramework\Core\Router\Router name($name) 
 * @method  TurFramework\Core\Router\Router getRoutes() 
 * @method  TurFramework\Core\Router\Router getNameList() 
 * @method  TurFramework\Core\Router\Router loadAllRoutesFiles() 
 * @method  TurFramework\Core\Router\Router resolve() 
 * @method  TurFramework\Core\Router\Router getByName($routeName)
 * 
 * @see TurFramework\Core\Router\Router
 */
class Route
{
    protected static $routerInstance = null;

    public function __construct(Request $request)
    {
        static::$routerInstance =  new Router($request);
    }

    public static function __callStatic($method, $args)
    {
        return call_user_func_array([static::$routerInstance, $method], $args);
    }

    public function __call($method, $args)
    {
        return call_user_func_array([static::$routerInstance, $method], $args);
    }
}
