<?php

namespace TurFramework\Application;

use TurFramework\Http\Request;
use TurFramework\Facades\Route;
use TurFramework\Container\Container;
use TurFramework\Configurations\ConfigLoader;
use TurFramework\Exceptions\ExceptionHandler;
use TurFramework\Exceptions\HttpResponseException;

class Application extends Container
{

    /**
     * The Tur framework version.
     *
     * @var string
     */
    public const VERSION = '1.0';


    public function __construct()
    {
        static::setInstance($this);

        // Register exceptions with the ExceptionHandler
        ExceptionHandler::registerExceptions();


        // 1- laod Configuration and bind config into the Container 
        $this->loadConfiguration();

        // 2- register Core Container Aliases
        $this->registerCoreContainerAliases();

        // 3- register service Providers
        $this->registerConfiguredProviders();
    }


    /**
     * run.
     */
    public function run(): void
    {
        // Resolve incoming HTTP request
        Route::resolve(new Request);
    }

    /**
     * Register a service provider with the application.
     *
     * @param  \TurFramework\Support\ServiceProvider|string  $provider
     * @return \TurFramework\Support\ServiceProvider
     */
    public function register($provider)
    {
        if (is_string($provider)) {
            $provider = $this->resolveProvider($provider);
        }
        $provider->register();
    }

    /**
     * Resolve a service provider instance from the class name.
     *
     * @param  string  $provider
     * @return \TurFramework\Support\ServiceProvider
     */
    public function resolveProvider($provider)
    {
        return new $provider($this);
    }

    /**
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerConfiguredProviders()
    {

        $providers = $this->make('config')->get('app.providers');

        foreach ($providers as $provider) {
            $this->register($provider);
        }
    }
    /**
     * load Configuration
     *
     * @return \TurFramework\Configurations\ConfigLoader
     */
    public function loadConfiguration()
    {
        return ConfigLoader::load($this);
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
     * @throws HttpResponseException
     */
    public function abort($code, $message = '')
    {
        throw new HttpResponseException(message: $message, code: $code);
    }

    protected function getCoreContainerAliases(): array
    {
        return [
            'router' =>  \TurFramework\Router\Router::class,
            'cache' => \TurFramework\Cache\Cache::class,
            'view' => \TurFramework\Views\Factory::class,
            'session' => \TurFramework\Session\Store::class,
            'redirect' => \TurFramework\Router\Redirector::class,
            'request' => \TurFramework\Http\Request::class,
        ];
    }

    /**
     * Register the core class aliases in the container.
     *
     * @return void
     */
    public function registerCoreContainerAliases()
    {
        $aliases = $this->getCoreContainerAliases();
        foreach ($aliases as $key => $alias) {
            if (is_array($alias)) {
                foreach ($alias as $value) {
                    $this->bind($key, $value);
                }
            } else {
                $this->bind($key, $alias);
            }
        }
    }
}
