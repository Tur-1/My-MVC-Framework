<?php


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

if (!function_exists('config')) {

    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\Illuminate\Config\Repository
     */
    function config($key = null, $default = null)
    {

        return;
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
        return base_path('views/' . $path);
    }
}

if (!function_exists('view')) {


    function view($view, $data = [])
    {
        // Get the path to the view file.
        $view_path = view_path($view);

        // Render the view file and return the output.
        return ob_get_clean();
    }
}
