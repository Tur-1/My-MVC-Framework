<?php

namespace TurFramework\Core\Facades;



/**

 * @method static mixed flash(string $key, $value = null) 
 * @method static void remove(string $key)
 * @method static void forget(array $keys)
 * @method static bool has(string $key)
 * @method static void put(string $key, $value)
 * @method static mixed get(string $key, $default = null)
 * 
 * @see \TurFramework\Core\Session\Store
 */
class Session extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'session';
    }
}
