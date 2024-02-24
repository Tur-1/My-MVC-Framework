<?php

namespace TurFramework\Router;

use TurFramework\Facades\Cache;
use TurFramework\Router\Exceptions\RouteException;

class RouteFileRegistrar
{

    /**
     * The router instance.
     *
     * @var \TurFramework\Router\Router
     */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Require the given routes file.
     *
     * @param    $routes
     * @return void
     */
    public function register($routes)
    {
        require $routes;
    }

    public function load($routes)
    {

        if ($this->routesAreCached()) {
            return  $this->loadCachedRoutes();
        } else {

            $this->loadRoutes($routes);

            // Cache::store($this->getCachedRoutesPath(), $this->router->getRouteCollection()->getRoutes());
        }
    }

    private function loadRoutes($routes)
    {
        foreach ($routes as $key => $routeFile) {
            $this->register($routeFile);
        }
    }

    protected function getCachedRoutesPath()
    {
        return 'bootstrap/cache/routes.php';
    }

    /**
     * Determine if the application routes are cached.
     *
     * @return bool
     */
    protected function routesAreCached()
    {
        return Cache::exists($this->getCachedRoutesPath());
    }
    /**
     * Load the cached routes for the application.
     *
     * @return void
     */
    protected function loadCachedRoutes()
    {

        return Cache::loadFile($this->getCachedRoutesPath());
    }



    protected function getRoutesFiles()
    {
        return get_files_in_directory('app/routes');
    }
}
