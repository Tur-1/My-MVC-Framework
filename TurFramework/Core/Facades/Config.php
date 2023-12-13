<?php


namespace TurFramework\Core\Facades;



/**
 * Facade for interacting with the Repository class.
 * 
 * @method static mixed get(string $key, $default = null) Get a configuration value by key.
 * @method static array getConfigurations() Get all configurations.
 * @method static void loadConfigurations() Load configurations from the Repository class.
 * 
 * @see \TurFramework\Core\Configurations\Config
 */
class Config extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'config';
    }
}
