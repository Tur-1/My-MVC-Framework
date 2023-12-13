<?php

namespace TurFramework\Core\Configurations;

use TurFramework\Core\Facades\Cache;

class Config
{
    private $configurations = [];


    public function __construct()
    {
    }

    /**
     * Load all configurations from files into an associative array.
     *
     * This method scans the config directory, reads each file, and extracts configurations.
     * The configurations are associated with their respective filenames in an array.
     *
     * @return array Associative array containing configurations keyed by filenames
     */
    protected function loadConfigFiles(): array
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
        return $configurations;
    }

    /**
     * Load configurations.
     *
     * Checks if cached configurations exist. If they do, loads them;
     *  otherwise, loads from config files and caches them.
     */
    public function loadConfigurations()
    {

        $configFile = base_path('bootstrap/cache/config.php');


        if (file_exists($configFile)) {
            // If cached configurations exist, load them directly
            $this->configurations = Cache::loadCachedFile($configFile);
        } else {
            // If cached configurations don't exist, load configurations from config files
            $this->configurations = $this->loadConfigFiles();

            // Cache the loaded configurations 
            Cache::cacheFile($configFile, $this->configurations);
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
        $config = $this->getConfigurations();

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
}
