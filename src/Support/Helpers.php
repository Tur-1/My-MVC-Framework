<?php

use src\Views\View;

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        return $_ENV[$key];
    }
}

if (!function_exists('app_path')) {

    function app_path($path = '')
    {

        return dirname(__DIR__) . '/../app/' . $path;
    }
}
if (!function_exists('get_all_php_files_in_directory')) {
    function get_all_php_files_in_directory($directory)
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }
        return $files;
    }
}
if (!function_exists('config')) {

    /**
     *
     *  This function should take two arguments:
     * 1- the name of the configuration file 
     * 2- the key of the value to be retrieved.
     * 
     * Inside the config() function, use the unserialize() function to read the contents of the configuration file and convert it into a PHP array.
     * 
     * @param  string $file
     * @param  string $key
     */
    function config($file)
    {


        $keys = explode('.', $file);
        $fileName = $keys[0];

        $path = require base_path('config/' . $fileName . '.php');
        // Read the contents of the configuration file

        $key = array_reverse($keys)[0];

        dd($path[$key]);
        // Return the value at the specified key in the array
        return $path[$key] ?? null;
    }
}
if (!function_exists('base_path')) {

    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\Illuminate\Config\Repository
     */
    function base_path($path = '')
    {

        return dirname(__DIR__) . '/../' . $path;
    }
}
if (!function_exists('view_path')) {


    function view_path($path)
    {
        return app_path('views/' . $path);
    }
}
if (!function_exists('view')) {


    function view($viewPath, array $params = [])
    {
        return View::render($viewPath, $params);
    }
}


if (!function_exists('includeComponent')) {


    function includeComponent($component, $data = [])
    {
        return View::renderComponent($component, $data);
    }
}
