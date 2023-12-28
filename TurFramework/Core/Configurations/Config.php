<?php

namespace TurFramework\Core\Configurations;

use TurFramework\Core\Facades\Cache;


class Config
{
    protected $configurations = [];


    public function __construct()
    {
        $this->loadConfigurations();
    }


    /**
     * Load all configurations from files into an associative array.
     *
     * This method scans the config directory, reads each file, and extracts configurations.
     * The configurations are associated with their respective filenames in an array.
     *
     */
    protected function loadConfigFiles()
    {
        // Initialize an empty array to store configurations
        $configurations = [];

        // Get the list of files in the config directory
        $configFiles = scandir(config_path());

        foreach ($configFiles as $configFile) {
            // Exclude the current directory (.) and parent directory (..)
            if ($configFile === '.' || $configFile === '..') {
                continue;
            }

            // Extract the configuration name from the file name
            $fileName = explode('.', $configFile)[0];

            // Load the configuration data from the file and associate it with its filename in the array
            $configurations[$fileName] = require config_path() . $configFile;
        }

        // Return the associative array containing configurations keyed by filenames
        $this->configurations = $configurations;
    }

    /**
     * Load configurations.
     *
     * Checks if cached configurations exist. If they do, loads them;
     *  otherwise, loads from config files and caches them.
     */
    public function loadConfigurations()
    {

        if ($this->configAreCached()) {
            // If cached configurations exist, load them directly
            $this->loadCachedConfig();
        } else {
            // If cached configurations don't exist, load configurations from config files
            $this->loadConfigFiles();

            // Cache the loaded configurations 
            Cache::store($this->getCachedConfigPath(), $this->getConfigurations());
        }
    }


    /**
     * Get a configuration value by key.
     *
     * @param string $key     The configuration key (can be dot-separated for nested configurations).
     * @param mixed  $default The default value to return if the key does not exist.
     *
     * @return mixed The configuration value.
     */
    public function get($key, $default = null)
    {

        // Split the key by dot to access nested configurations
        $keys = explode('.', $key);

        // Retrieve all configurations
        $config =  $this->getConfigurations();

        // Traverse through nested configurations based on the key
        foreach ($keys as $value) {
            // Check if the key exists in the current level of configurations
            if (isset($config[$value])) {
                // Move deeper into the nested configuration
                $config = $config[$value];
            } else {
                // If the key doesn't exist, assign the default value and exit the loop
                $config = $default;
                break;
            }
        }

        // Return the final configuration value or the default value if not found
        return $config;
    }
    /**
     * Get all configurations.
     *
     * @return array All configurations.
     */
    public function getConfigurations()
    {
        return $this->configurations;
    }
    /**
     * Load the cached routes for the application.
     *
     * @return void
     */
    protected function loadCachedConfig()
    {

        $this->configurations = Cache::loadFile($this->getCachedConfigPath());
    }

    /**
     * Determine if the application routes are cached.
     *
     * @return bool
     */
    protected function configAreCached()
    {
        return Cache::exists($this->getCachedConfigPath());
    }


    protected function getCachedConfigPath()
    {
        return 'bootstrap/cache/config.php';
    }
}
