<?php

namespace TurFramework\src;

use TurFramework\src\Http\Request;
use TurFramework\src\Router\Route;
use TurFramework\src\Http\Response;
use TurFramework\src\Support\Config;

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

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();

        $this->route = new Route($this->request, $this->response);

        $this->config = new Config($this->loadConfig());
        $this->loadAllRoutesFiles();
    }

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

    /**
     * load all Routes files.
     */
    public function loadAllRoutesFiles()
    {
        $routesFiles = get_all_php_files_in_directory('app/routes');

        if (empty($routesFiles)) {
            echo 'no routes files found';
            exit();
        }

        foreach ($routesFiles as $routeFile) {
            require_once $routeFile;
        }
    }
}
