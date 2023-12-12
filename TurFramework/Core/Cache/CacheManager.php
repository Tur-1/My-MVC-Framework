<?php

namespace TurFramework\Core\Cache;

class CacheManager
{

    public function loadCachedFile(string $cacheFile)
    {
        return require $cacheFile;
    }

    public function cacheFile(string $file, array $content)
    {
        file_put_contents($file, '<?php return ' . var_export($content, true) . ';');
    }
}
