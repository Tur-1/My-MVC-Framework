<?php

namespace TurFramework\Facades;

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

    /**
     * Get the root object behind the facade.
     *
     * @return mixed
     */
    public static function getFacadeRoot()
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }
    protected static function createFacadeInstance($facadeAccessor)
    {

        return app()->make($facadeAccessor);
    }
    /**
     * Resolve the facade root instance from the container.
     *
     * @param  string  $name
     * @return mixed
     */
    protected static function resolveFacadeInstance($facadeAccessor)
    {
        if (static::isNotExsits($facadeAccessor)) {
            static::addResolvedInstances($facadeAccessor, static::createFacadeInstance($facadeAccessor));
        }

        return static::getResolvedInstances($facadeAccessor);
    }

    private static function isNotExsits($facadeAccessor)
    {
        return !isset(static::$resolvedInstances[$facadeAccessor]);
    }
    private static function addResolvedInstances($facadeAccessor, $instance)
    {
        return static::$resolvedInstances[$facadeAccessor] = $instance;
    }

    private static function getResolvedInstances($facadeAccessor)
    {
        return static::$resolvedInstances[$facadeAccessor];
    }
    public static function __callStatic($method, $args)
    {
        $instance = static::getFacadeRoot();

        return $instance->{$method}(...$args);
    }
}
