<?php


namespace TurFramework\Core\Facades;


use TurFramework\Core\Configurations\Repository;

/**
 * Facade for interacting with the Repository class.
 * 
 * @method static mixed get(string $key, $default = null) Get a configuration value by key.
 * @method static array getConfigurations() Get all configurations.
 * @method static void loadConfigurations() Load configurations from the Repository class.
 * 
 * @see \TurFramework\Core\Configurations\Repository
 */
class Config extends Facade
{
    protected static function getFacadeAccessor()
    {
        return new Repository();
    }
}
