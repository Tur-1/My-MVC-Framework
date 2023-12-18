<?php

namespace TurFramework\Core\Application;

use InvalidArgumentException;
use TurFramework\Core\Facades\Config;
use TurFramework\Core\Facades\Request;
use TurFramework\Core\Container\Container;
use TurFramework\Core\Exceptions\ExceptionHandler;
use TurFramework\Core\Router\RouteNotDefinedException;
use TurFramework\Core\Exceptions\HttpResponseException;

class Application
{

    private static ?Application $instance = null;

    /**
     * The Tur framework version.
     *
     * @var string
     */
    public const VERSION = '1.0';
    public Container $container;



    // Prevent direct instantiation of the class


    private function __construct()
    {

        // Register exceptions with the ExceptionHandler
        ExceptionHandler::registerExceptions();
        // Create a new container instance
        $this->setContainer(new Container);
        // Register core aliases into the container
        $this->container->registerCoreAliases($this->getCoreAliases());
    }



    /**
     * run.
     */
    public function run(): void
    {
        // Load configurations using the Config class
        Config::loadConfigurations();

        Request::sendRequestThroughRouter();
    }
    /**
     * Get the singleton instance of Application.
     * 
     * @return Application The singleton instance of the Application class.
     */
    public static function getInstance(): Application
    {
        // If no instance exists, create a new one
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function bind($key, $value)
    {
        $this->container->bind($key, $value);
    }
    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function resolve($key)
    {
        return $this->container->resolve($key);
    }

    public static function __callStatic($method, $args)
    {
        return static::getInstance()->resolve($method);
    }


    /**
     * Method to start the application.
     * 
     * Starts the application by invoking the run method on the singleton instance.
     */
    public static function start(): void
    {
        // Start the application by running the run method of the singleton instance
        self::getInstance()->run();
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
        $route = Route::getByName($routeName);

        dd($route);

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

    protected function getCoreAliases(): array
    {
        return   [
            'cache' => \TurFramework\Core\Cache\Cache::class,
            'view' => \TurFramework\Core\Views\Factory::class,
            'session' => \TurFramework\Core\Session\Store::class,
            'redirect' => \TurFramework\Core\Router\Redirector::class,
            'config' => \TurFramework\Core\Configurations\Config::class,
            'request' => \TurFramework\Core\Http\Request::class,
            'route' => \TurFramework\Core\Router\Router::class,
        ];
    }
}
