<?php

namespace TurFramework\src\Router;

use TurFramework\src\Facades\Cache;
use TurFramework\src\Router\Exceptions\RouteException;

class RouteFileRegistrar
{

    /**
     * The router instance.
     *
     * @var \TurFramework\src\Router\Router
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

    /**
     * Loads route files from the 'app/routes' directory.
     * Throws an exception if no route files are found.
     */
    protected function loadRoutesFiles()
    {

        if (empty($this->getRoutesFiles())) {
            throw RouteException::routeFilesNotFound();
        }


        foreach ($this->getRoutesFiles() as $routeFile) {
            require $routeFile;
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
