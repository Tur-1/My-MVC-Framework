<?php

namespace TurFramework\Core;

use TurFramework\Core\Http\Request;
use TurFramework\Core\Router\Route;
use TurFramework\Core\Http\Response;
use TurFramework\Core\Support\Config;
use TurFramework\Core\Exceptions\ExceptionHandler;

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
        $this->request = new Request();
        $this->response = new Response();

        $this->route = new Route($this->request, $this->response);

        $this->config = new Config($this->loadConfig());
    }

    /**
     * run.
     */
    public function run(): void
    {
        $this->route->reslove();
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
     * load all config files.
     */
    protected function loadConfig()
    {
        foreach (scandir(config_path()) as $configFile) {
            if ($configFile == '.' || $configFile == '..') {
                continue;
            }

            $fileName = explode('.', $configFile)[0];

            yield $fileName => require config_path().$configFile;
        }
    }
}
