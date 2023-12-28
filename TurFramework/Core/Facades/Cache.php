<?php

namespace TurFramework\Core\Facades;

/**
 * @method static void store(string $file, mixed $data)
 * @method static mixed|null loadFile(string $cacheFile)
 * @method static mixed|null exists(string $file)
 * @see \TurFramework\Core\Cache\Cache
 */
class Cache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cache';
    }
}
