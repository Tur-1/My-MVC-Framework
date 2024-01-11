<?php

namespace TurFramework\src\Cache;

class Cache
{

    public function exists(string $file)
    {
        return file_exists(base_path($file));
    }
    public  function loadFile(string $file)
    {

        if (!$this->exists($file)) {
            throw CacheException::fileNotFound($file);
        }
        return require base_path($file);
    }

    public function store(string $file, array $content)
    {
        file_put_contents(base_path($file), '<?php return ' . var_export($content, true) . ';');
    }
}
