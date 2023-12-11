<?php

use TurFramework\Core\Application;
use TurFramework\Core\Facades\View;
use TurFramework\Core\Facades\Session;
use TurFramework\Core\Facades\Redirect;

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
        return $_ENV[$key] ?? value($default);
    }
}

if (!function_exists('value')) {

    function value($value)
    {
        return ($value instanceof Closure) ? $value() : $value;
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

if (!function_exists('get_files_in_directory')) {
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
    function app_path($path = '')
    {
        return base_path('app/' . $path);
    }
}
if (!function_exists('base_path')) {
    function base_path($path = '')
    {
        return dirname(__DIR__) . '/../../' . $path;
    }
}
if (!function_exists('view_path')) {
    function view_path($path)
    {
        return base_path('app/views/' . $path);
    }
}
if (!function_exists('view')) {
    function view($viewPath, array $data = [])
    {
        return View::make($viewPath, $data);
    }
}

if (!function_exists('import')) {
    function import($viewPath, array $data = [])
    {
        return View::make($viewPath, $data);
    }
}

if (!function_exists('redirect')) {
    function redirect()
    {
        return new Redirect();
    }
}

if (!function_exists('session')) {
    function session($key, $default = null)
    {
        return Session::get($key, $default);
    }
}

if (!function_exists('old')) {
    function old($key, $default = null)
    {
        return Session::get('old')[$key] ?? $default;
    }
}

if (!function_exists('abort')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param  int  $code
     * @param  string  $message
     * @return never
     */
    function abort($code = 404, $message = '')
    {
        app()->abort($code, $message);
    }
}
if (!function_exists('request')) {
    /**
     *
     *
     */
    function request()
    {
        return app()->request;
    }
}
if (!function_exists('route')) {
    /**
     *
     *
     */
    function route($routeName, $parameters = [])
    {
        return app()->route($routeName, $parameters);
    }
}
