<?php

namespace TurFramework\Core\Cache;

class Cache
{

    public static function loadCachedFile(string $cacheFile)
    {
        return require $cacheFile;
    }

    public static function cacheFile(string $file, array $content)
    {
        file_put_contents($file, '<?php return ' . var_export($content, true) . ';');
    }
}
