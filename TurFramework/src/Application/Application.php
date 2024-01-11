<?php

namespace TurFramework\src\Application;

use TurFramework\src\Http\Request;
use TurFramework\src\Facades\Route;
use TurFramework\src\Container\Container;
use TurFramework\src\Exceptions\ExceptionHandler;
use TurFramework\src\Configurations\LoadConfiguration;
use TurFramework\src\Exceptions\HttpResponseException;

class Application extends Container
{

    /**
     * The Tur framework version.
     *
     * @var string
     */
    public const VERSION = '1.0';
    /**
     * The singleton instance of the Container.
     *
     * @var self|null
     */
    protected static $appInstance;

    public function __construct()
    {
        // Register exceptions with the ExceptionHandler
        ExceptionHandler::registerExceptions();
        // load config
        LoadConfiguration::load($this);
    }


    /**
     * run.
     */
    public function run(): void
    {

        // Register core services into the container
        $this->registerApplicationServices();
        // Register the base service providers
        $this->registerBaseServiceProvider();

        // Resolve incoming HTTP request
        Route::resolve(new Request);
    }
    /**
     * Register the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProvider()
    {
        $providers = $this->resolve('config')->get('app.providers');

        foreach ($providers as $providerClass) {
            $provider = new $providerClass($this);
            if (method_exists($provider, 'register')) {
                $provider->register();
            }
        }
    }
    /**
     * Get the singleton instance of Application.
     * 
     * @return $appInstance
     */
    public static function getApplicationInstance()
    {
        // If no instance exists, create a new one
        if (is_null(static::$appInstance)) {
            static::$appInstance = new static;
        }

        return static::$appInstance;
    }

    /**
     * Method to start the application.
     * 
     * Starts the application by invoking the run method on the singleton instance.
     */
    public static function start(): void
    {

        self::getApplicationInstance()->run();
    }
    /**
     * Binds a specified abstract to a particular value within the container.
     *
     * @param string $abstract
     * @param mixed  $concrete 
     * @return void
     */
    public function bind($abstract, $concrete)
    {
        parent::bind($abstract, $concrete);
    }


    /**
     * Resolves and retrieves the value associated with the given key from the container.
     *
     * @param string $abstract
     * @return mixed
     */
    public function resolve($abstract)
    {
        return parent::resolve($abstract);
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

    protected function getCoreServices(): array
    {
        return   [
            'cache' => \TurFramework\src\Cache\Cache::class,
            'view' => \TurFramework\src\Views\Factory::class,
            'session' => \TurFramework\src\Session\Store::class,
            'redirect' => \TurFramework\src\Router\Redirector::class,
            'request' => \TurFramework\src\Http\Request::class,
            'route' =>  \TurFramework\src\Router\Router::class,

        ];
    }
    public function getRouteByName($routeName, $params)
    {
        return Route::getRouteByName($routeName, $params);
    }
    /**
     * Register core services and their dependencies into the container.
     *
     * @param array $services
     * @return void
     */
    public function registerApplicationServices()
    {
        $services = $this->getCoreServices();

        foreach ($services as $key => $service) {
            $this->bind($key, $service);
        }
    }
}
