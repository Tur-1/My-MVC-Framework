<?php

namespace TurFramework\Core\Application;

use InvalidArgumentException;
use TurFramework\Core\Http\Request;
use TurFramework\Core\Facades\Route;
use App\Providers\AppServiceProvider;
use TurFramework\Core\Exceptions\ExceptionHandler;
use TurFramework\Core\Router\RouteNotDefinedException;
use TurFramework\Core\Exceptions\HttpResponseException;
use TurFramework\Core\Router\Exceptions\RouteException;

class Application extends AppServiceProvider
{


    /**
     * The singleton instance of the Container.
     *
     * @var self|null
     */
    protected static $appInstance;

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

        // Register core services into the container
        $this->registerApplicationServices();
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
     * run.
     */
    public function run(): void
    {

        Request::getInstance()->sendRequestThroughRouter();
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
            'cache' => \TurFramework\Core\Cache\Cache::class,
            'view' => \TurFramework\Core\Views\Factory::class,
            'session' => \TurFramework\Core\Session\Store::class,
            'redirect' => \TurFramework\Core\Router\Redirector::class,
            'config' => \TurFramework\Core\Configurations\Config::class,
            'request' => \TurFramework\Core\Http\Request::class,
            'route' => (new \TurFramework\Core\Router\Router())->getInstance(),
        ];
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

        $this->register();
    }
}
