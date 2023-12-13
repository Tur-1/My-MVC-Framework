<?php

namespace TurFramework\Core\Facades;

/**
 * @method static void cacheFile(string $file, mixed $data)
 * @method static mixed|null loadCachedFile(string $cacheFile)
 *
 * @see \TurFramework\Core\Cache\Cache
 */
class Cache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cache';
    }
}
