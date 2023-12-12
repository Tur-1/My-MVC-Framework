<?php

namespace TurFramework\Core\Facades;

use BadMethodCallException;

abstract class Facade
{
    protected static $resolvedInstances;

    abstract protected static function getFacadeAccessor();


    protected static function createFacadeInstance()
    {
        return new static::$resolvedInstances();
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
        $instance = static::getFacadeAccessor();

        return $instance->{$method}(...$args);
    }

    public function __call($method, $args)
    {
        $instance = static::getFacadeAccessor();

        return $instance->{$method}(...$args);
    }
  
}
