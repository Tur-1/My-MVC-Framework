<?php

namespace TurFramework\src\Facades;


/**
 * @method static \TurFramework\src\Router\Router get(string $uri, string|array|Closure $action = null)
 * @method static \TurFramework\src\Router\Router post(string $uri, array|string|callable|null $action = null)
 * @method static \TurFramework\src\Router\Router delete(string $uri, array|string|callable|null $action = null)
 * @method static \TurFramework\src\Router\Router controller($controller) 
 * @method static \TurFramework\src\Router\Router group($routes) 
 * @method static \TurFramework\src\Router\Router name(string $name) 
 * @see \TurFramework\src\Router\Router
 */
class Route extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'route';
    }
}
