<?php

namespace TurFramework\src\Facades;

use RuntimeException;

abstract class Facade
{
    protected static $resolvedInstances = [];

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
    }

    protected static function createFacadeInstance()
    {

        return app()->resolve(static::getFacadeAccessor());
    }

    protected static function getResolvedInstance($facadeAccessor)
    {
        if (!isset(static::$resolvedInstances[$facadeAccessor])) {
            static::$resolvedInstances[$facadeAccessor] = static::createFacadeInstance($facadeAccessor);
        }


        return static::$resolvedInstances[$facadeAccessor];
    }

    public static function __callStatic($method, $args)
    {

        $instance = static::getResolvedInstance(static::getFacadeAccessor());

        return $instance->{$method}(...$args);
    }
}
