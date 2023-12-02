<?php

namespace TurFramework\Core;

use InvalidArgumentException;
use TurFramework\Core\Http\Request;
use TurFramework\Core\Facades\Route;
use TurFramework\Core\Http\Response;
use TurFramework\Core\Support\Config;
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
    protected Response $response;
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
        $this->response = new Response();
        $this->route = new Route($this->request, $this->response);

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
    public function route($routeName, $parameters = [])
    {

        $route = $this->route->getByName($routeName);
        //"Missing required parameter for [Route: $routeName] [URI: home/{user_id}] [Missing parameter: user_id]."
        if (is_null($route)) {
            throw new RouteNotDefinedException("Route [ $routeName ] not defined.");
        }

        // "uri" => "/about/{name}"
        // "method" => "POST"
        // "controller" => "App\Http\Controllers\HomeController"
        // "action" => "about"
        // "parameters" => array:1 [▼
        //   0 => "name"
        // ]
        // "name" => "aboutPage"

        foreach ($parameters as $key => $value) {
            if (!in_array($key, $route['parameters'])) {
                throw new InvalidArgumentException("Missing required parameter for [Route: $routeName] [URI: " . $route['uri'] . " ] [Missing parameter: $key].");
            }
        }


        // foreach ($this->route->getNameList() as $route => $routeDetails) {
        //     if ($routeDetails['name'] === $routeName) {
        //         $url = $routeDetails['uri'];
        //         foreach ($parameters as $key => $value) {
        //             $url = str_replace('{' . $key . '}', $value, $url);
        //         }
        //         return $url;
        //         break;
        //     }
        // }
        return '';
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
