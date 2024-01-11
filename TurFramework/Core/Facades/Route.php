<?php

namespace TurFramework\Core\Facades;


/**
 * @method static \TurFramework\Core\Router\Router get(string $uri, string|array|Closure $action = null)
 * @method static \TurFramework\Core\Router\Router post(string $uri, array|string|callable|null $action = null)
 * @method static \TurFramework\Core\Router\Router delete(string $uri, array|string|callable|null $action = null)
 * @method static \TurFramework\Core\Router\Router controller($controller) 
 * @method static \TurFramework\Core\Router\Router group($routes) 
 * @method static \TurFramework\Core\Router\Router name(string $name) 
 * @see \TurFramework\Core\Router\Router
 */
class Route extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'route';
    }
}
