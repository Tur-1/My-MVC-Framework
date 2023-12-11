<?php

namespace TurFramework\Core;

use InvalidArgumentException;
use TurFramework\Core\Facades\Route;
use TurFramework\Core\Support\Config;
use TurFramework\Core\Facades\Request;
use TurFramework\Core\Exceptions\ExceptionHandler;
use TurFramework\Core\Router\RouteNotDefinedException;
use TurFramework\Core\Exceptions\HttpResponseException;


class Application
{
    /**
     * The Tur framework version.
     *
     * @var string
     */
    public const VERSION = '1.0';

    protected Request $request;
    protected Route $route;
    protected Config $config;

    /**
     * __construct.
     *
     * @return void
     */
    public function __construct()
    {

        ExceptionHandler::registerExceptions();
        $this->config = new Config($this->loadConfig());
        $this->request = new Request();
        $this->route = new Route($this->request);
        $this->route->loadAllRoutesFiles();
    }

    /**
     * run.
     */
    public function run(): void
    {

        $this->route->resolve();
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version()
    {
        return static::VERSION;
    }

    /**
     * Get app property.
     *
     * @param mixed name
     *
     * @return $name
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }

    /**
     * Determine if the application is in the local environment.
     *
     * @return bool
     */
    public function isLocal()
    {
        return env('APP_ENV') === 'local';
    }

    /**
     * Determine if the application is in the production environment.
     *
     * @return bool
     */
    public function isProduction()
    {
        return env('APP_ENV') === 'production';
    }

    /**
     * Throw an HttpException with the given data.
     *
     * @param  int  $code
     * @param  string  $message 
     * @return never
     *
     * @throws HttpResponseException
     *
     */
    public function abort($code, $message = '')
    {
        throw new HttpResponseException(message: $message, code: $code);
    }

    /**
     * Generates a URL based on the route name and parameters.
     *
     * @param string $routeName The name of the route
     * @param array $parameters (Optional) Parameters for the route
     * @return string The generated URL
     * @throws RouteNotDefinedException When the route is not defined
     * @throws InvalidArgumentException When required parameters are missing
     */
    public function route($routeName, $parameters = [])
    {

        $route = $this->route->getByName($routeName);


        if (is_null($route)) {
            throw new RouteNotDefinedException("Route [ $routeName ] not defined.");
        }
        foreach ($route['parameters'] as $key => $value) {
            if (!in_array($value, array_keys($parameters))) {
                throw new InvalidArgumentException("Missing required parameter for [Route: $routeName] [URI: " . $route['uri'] . " ]  [ Missing parameter: $value ].");
            }
        }

        $url = $route['uri'];
        foreach ($parameters as $key => $value) {
            $url = str_replace('{' . $key . '}', $value, $url);
        }
        return $url;
    }

    /**
     * load all config files.
     */
    protected function loadConfig()
    {

        foreach (scandir(config_path()) as $configFile) {
            if ($configFile == '.' || $configFile == '..') {
                continue;
            }

            $fileName = explode('.', $configFile)[0];

            yield $fileName => require config_path() . $configFile;
        }
    }
}
