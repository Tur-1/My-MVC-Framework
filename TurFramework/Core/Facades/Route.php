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
class Route extends Facade
{
    protected static $routerInstance = null;

    public function __construct(Request $request)
    {
        static::$routerInstance =  new Router($request);
    }

    protected static function getFacadeAccessor()
    {
        return  static::$routerInstance;
    }
}
