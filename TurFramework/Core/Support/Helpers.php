<?php

use TurFramework\Core\Facades\Config;
use TurFramework\Core\Application\Application;
use TurFramework\Core\Container\Container;

if (!function_exists('app')) {
    /**
     * Get the available container instance or resolve an abstract.
     *
     * @param  string|null  $abstract
     * @return \TurFramework\Core\Application\Application|mixed
     */

    function app($abstract = null)
    {

        if (is_null($abstract)) {
            return Application::getApplicationInstance();
        }

        return Application::getApplicationInstance()->resolve($abstract);
    }
}

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        return $_ENV[$key] ?? value($default);
    }
}

if (!function_exists('value')) {
    /**
     * Gets the value or executes a closure if the value is a closure.
     *
     * @param mixed $value
     * @return mixed
     */
    function value($value)
    {

        return ($value instanceof Closure) ? $value() : $value;
    }
}
if (!function_exists('config')) {
    /**
     * Get the specified configuration value.
     *
     * @param string $key
     * @param  mixed $default
     * @return mixed
     */
    function config($key = null, $default = null)
    {

        return app('config')->get($key, $default);
    }
}


if (!function_exists('get_files_in_directory')) {
    /**
     * Gets an array of files in a directory.
     *
     * @param string $directory
     * @return array
     */
    function get_files_in_directory($directory)
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
    /**
     * Gets the path to the application directory.
     *
     * @param string $path
     * @return string
     */
    function app_path($path = '')
    {
        return base_path('app/' . $path);
    }
}
if (!function_exists('base_path')) {
    /**
     * Gets the base path of the application.
     *
     * @param string $path
     * @return string
     */
    function base_path($path = '')
    {
        return dirname(__DIR__) . '/../../' . $path;
    }
}
if (!function_exists('view_path')) {
    /**
     * Gets the path to a view file.
     *
     * @param string $path
     * @return string
     */
    function view_path($path)
    {
        return base_path('app/views/' . $path);
    }
}
if (!function_exists('config_path')) {
    /**
     * Gets the path to the configuration directory.
     *
     * @return string
     */
    function config_path()
    {
        return base_path('config/');
    }
}

if (!function_exists('view')) {
    /**
     * Render a view with optional data.
     *
     * @param  string  $viewPath  The path to the view to be rendered
     * @param  array   $data      Optional data to be passed to the view
     * @return \TurFramework\Core\Views\ViewFactory
     */
    function view($viewPath, array $data = [])
    {
        return app('view')->make($viewPath, $data);
    }
}


if (!function_exists('import')) {
    /**
     * Render a view with optional data.
     *
     * @param  string  $viewPath  The path to the view to be rendered
     * @param  array   $data      Optional data to be passed to the view
     * @return \TurFramework\Core\Views\ViewFactory
     */
    function import($viewPath, array $data = [])
    {
        return app('view')->make($viewPath, $data);
    }
}

if (!function_exists('redirect')) {

    /**
     * Retrieves an instance of the Redirector class.
     *
     * @return \TurFramework\Core\Router\Redirector
     */
    function redirect()
    {
        return app('redirect');
    }
}


if (!function_exists('session')) {
    /**
     * Get / set the specified session value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\TurFramework\Core\Session\Store
     */
    function session($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('session');
        }

        if (is_array($key)) {
            return app('session')->put($key);
        }

        return app('session')->get($key, $default);
    }
}

if (!function_exists('old')) {
    /**
     * Get old input data from the session.
     *
     * @param  string  $key The key of the old input to retrieve from the session
     * @param  mixed   $default  The default value to return if the key is not found
     * @return mixed
     */
    function old($key, $default = null)
    {
        return app('session')->get('old')[$key] ?? $default;
    }
}


if (!function_exists('abort')) {
    /**
     * Throws an HttpException with the given data.
     *
     * @param int    $code
     * @param string $message
     * @return never
     */
    function abort($code = 404, $message = '')
    {
        app()->abort($code, $message);
    }
}
if (!function_exists('request')) {
    /**
     * Retrieves an instance of the current request or an input item from the request.
     *
     * @return \TurFramework\Core\Http\Request
     */
    function request()
    {

        return app('request');
    }
}
if (!function_exists('route')) {
    /**
     * Retrieves a route by name and parameters.
     *
     * @param string $routeName
     * @param array  $parameters
     * @return mixed
     */
    function route($routeName, $parameters = [])
    {

        return app('route')->route($routeName, $parameters);
    }
}
