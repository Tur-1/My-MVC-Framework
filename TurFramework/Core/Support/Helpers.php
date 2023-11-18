<?php

use TurFramework\Core\Application;
use TurFramework\Core\Views\View;

if (!function_exists('app')) {
    function app()
    {
        static $instance = null;

        if (!$instance) {
            $instance = new Application();
        }

        return $instance;
    }
}

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    function env($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}
if (!function_exists('config')) {
    /**
     * Gets the value of config variable.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    function config($key)
    {
        return app()->config->get($key);
    }
}

if (!function_exists('config_path')) {
    function config_path()
    {
        return base_path('config/');
    }
}

if (!function_exists('get_all_php_files_in_directory')) {
    function get_all_php_files_in_directory($directory)
    {
        $files = [];

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(base_path($directory)));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }
}

if (!function_exists('app_path')) {
    function app_path($path = '')
    {
        return base_path('app/'.$path);
    }
}
if (!function_exists('base_path')) {
    function base_path($path = '')
    {
        return dirname(__DIR__).'/../../'.$path;
    }
}
if (!function_exists('view_path')) {
    function view_path($path)
    {
        return base_path('app/views/'.$path);
    }
}
if (!function_exists('view')) {
    function view($viewPath, array $params = [])
    {
        return View::render($viewPath, $params);
    }
}

if (!function_exists('import')) {
    function import($component, $data = [])
    {
        return View::importComponent($component, $data);
    }
}
