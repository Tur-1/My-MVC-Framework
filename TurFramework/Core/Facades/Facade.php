<?php

namespace TurFramework\Core\Facades;

use BadMethodCallException;

abstract class Facade
{

    protected static $resolvedInstance;

    abstract protected static function getFacadeAccessor();

    protected static function resolveFacadeInstance()
    {

        static::$resolvedInstance = static::getFacadeAccessor();

        return static::$resolvedInstance;
    }

    public static function __callStatic($method, $args)
    {
        $instance = static::resolveFacadeInstance();

        if (method_exists($instance, $method)) {
            return $instance->{$method}(...$args);
        }

        throw new BadMethodCallException("Method {$method} does not exist.");
    }

    public function __call($method, $args)
    {
        $instance = static::resolveFacadeInstance();

        if (method_exists($instance, $method)) {
            return $instance->{$method}(...$args);
        }

        throw new BadMethodCallException("Method {$method} does not exist.");
    }
}
