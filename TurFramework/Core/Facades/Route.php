<?php

namespace TurFramework\Core\Facades;

use TurFramework\Core\Router\Router;
use TurFramework\Core\Facades\Request;


/**
 * @method static \TurFramework\Core\Router\Router get(string $uri, string|array|Closure $action = null)
 * @method static \TurFramework\Core\Router\Router post(string $uri, array|string|callable|null $action = null)
 * @method static \TurFramework\Core\Router\Router delete(string $uri, array|string|callable|null $action = null)
 * @method static \TurFramework\Core\Router\Router controller($controller) 
 * @method static \TurFramework\Core\Router\Router group($callback) 
 * @method static \TurFramework\Core\Router\Router name(string $name) 
 * @method array getRoutes() 
 * @method array getNameList() 
 * @method static \TurFramework\Core\Router\Router loadRotues() 
 * @method void resolve() 
 * @method array|null getByName(string $routeName)
 * 
 * @see \TurFramework\Core\Router\Router
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
        return  static::$routerInstance->$method(...$args);
    }

    public function __call($method, $args)
    {
        return  static::$routerInstance->$method(...$args);
    }
}
