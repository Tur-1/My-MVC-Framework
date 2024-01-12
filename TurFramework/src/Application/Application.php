<?php

namespace TurFramework\Application;

use TurFramework\Http\Request;
use TurFramework\Facades\Route;
use TurFramework\Container\Container;
use TurFramework\Exceptions\ExceptionHandler;
use TurFramework\Configurations\LoadConfiguration;
use TurFramework\Exceptions\HttpResponseException;

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

        // Register core services into the container
        $this->registerApplicationServices();

        // load config
        LoadConfiguration::load($this);
    }


    /**
     * run.
     */
    public function run(): void
    {

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
            if (method_exists($providerClass, 'register')) {
                $provider = new $providerClass($this);
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
            'config' =>  \TurFramework\Configurations\Repository::class,
            'cache' => \TurFramework\Cache\Cache::class,
            'view' => \TurFramework\Views\Factory::class,
            'session' => \TurFramework\Session\Store::class,
            'redirect' => \TurFramework\Router\Redirector::class,
            'request' => \TurFramework\Http\Request::class,
            'route' =>  \TurFramework\Router\Router::class
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
