<?php


namespace TurFramework\Core\Facades;



/**
 * Facade for interacting with the Repository class.
 * 
 * @method static mixed get(string $key, $default = null)
 * @method static bool has($key)
 * @method static array all()
 * @method static void push($key, $value)
 * @method static void prepend($key, $value)
 * @method static void set($key, $value = null)
 * @method static array getMany($keys)
 * 
 * @see \TurFramework\Core\Configurations\Repository
 */
class Config extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'config';
    }
}
