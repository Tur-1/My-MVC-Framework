<?php

namespace TurFramework\Configurations;

use TurFramework\Facades\Cache;
use TurFramework\Configurations\Repository;

class LoadConfiguration
{


    /**
     * Load configurations.
     *
     * Checks if cached configurations exist. If they do, loads them;
     *  otherwise, loads from config files and caches them.
     */
    public static function load($app)
    {
        $items = [];
        $instance = new self();

        if (file_exists($cached = $instance->getCachedConfigPath())) {
            // If cached configurations exist, load them directly
            $items =  require $cached;
            $loadedFromCache = true;
        }

        $config = new Repository($items);

        $app->bind('config', fn () => $config);

        if (!isset($loadedFromCache)) {
            $instance->loadConfigFiles($config);
        }
    }
    /**
     * Determine if the application configuration is cached.
     *
     * @return bool
     */
    public function configurationIsCached()
    {
        return is_file($this->getCachedConfigPath());
    }

    /**
     * Get the path to the configuration cache file.
     *
     * @return string
     */
    public function getCachedConfigPath()
    {
        return base_path('bootstrap/cache/config.php');
    }
    /**
     * Load all configurations from files
     * 
     */
    protected function loadConfigFiles($repository)
    {
        $files = get_files_in_directory('config');

        foreach ($files as $key => $path) {
            $key = basename($path, '.php');
            $repository->set($key, require $path);
        }
    }
}
