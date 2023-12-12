<?php

namespace TurFramework\Core\Facades;

use TurFramework\Core\Cache\CacheManager;

/**
 * @method static void cacheFile(string $file, mixed $data)
 * @method static mixed|null loadCachedFile(string $cacheFile)
 *
 * @see \CacheManager
 */
class Cache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return new CacheManager();
    }
}
