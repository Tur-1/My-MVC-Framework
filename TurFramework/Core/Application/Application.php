<?php

namespace TurFramework\Core\Application;

use InvalidArgumentException;
use TurFramework\Core\Facades\Route;
use TurFramework\Core\Http\Request;
use TurFramework\Core\Container\Container;
use TurFramework\Core\Exceptions\ExceptionHandler;
use TurFramework\Core\Router\RouteNotDefinedException;
use TurFramework\Core\Exceptions\HttpResponseException;

class Application extends Container
{

    public static ?Application $appInstance = null;

    /**
     * The Tur framework version.
     *
     * @var string
     */
    public const VERSION = '1.0';

    public function __construct()
    {

        // Register exceptions with the ExceptionHandler
        ExceptionHandler::registerExceptions();

        // Register core aliases into the container
        $this->registerCoreContainerAliases($this->getCoreAliases());
    }



    /**
     * run.
     */
    public function run(): void
    {

        Request::getInstance()->sendRequestThroughRouter();
    }


    /**
     * Binds a specified key to a particular value within the container.
     *
     * @param string $key   The key to bind.
     * @param mixed  $value The value to associate with the key.
     * @return void
     */
    public function bind($key, $value)
    {
        parent::bind($key, $value);
    }


    /**
     * Resolves and retrieves the value associated with the given key from the container.
     *
     * @param string $key The key whose value needs to be resolved.
     * @return mixed|null The resolved value if found; otherwise, returns null.
     */
    public function resolve($key)
    {
        return parent::resolve($key);
    }

    /**
     * Get the singleton instance of Application.
     * 
     * @return Application The singleton instance of the Application class.
     */
    public static function getApplicationInstance(): Application
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

    /**
     * Register core aliases and their dependencies into the container.
     *
     * @param array $aliases
     * @return void
     */
    public function registerCoreContainerAliases($aliases)
    {
        // 

        foreach ($aliases as $key => $alias) {
            if ($this->hasDependencies($alias)) {
                $this->bind($key, function () use ($alias) {
                    return new $alias['class'](...$this->resolveDependencies($alias));
                });
            } else {
                $this->bind($key, function () use ($alias) {
                    return new $alias();
                });
            }
        }
    }


    private function hasDependencies($alias)
    {
        return is_array($alias) && $alias['dependencies'];
    }
    private function resolveDependencies($alias)
    {
        $dependencies = [];
        foreach ($alias['dependencies'] as $dependency) {

            $dependencies[] = $this->resolve($dependency);
        }

        return $dependencies;
    }
}
