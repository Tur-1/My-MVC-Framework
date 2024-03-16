<?php

namespace TurFramework\Http;

use Exception;
use TurFramework\Router\Router;
use TurFramework\Exceptions\ExceptionHandler;

class HttpKernel
{

    /**
     * The router instance.
     *
     * @var \TurFramework\Router\Router
     */
    protected $router;
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [];

    private $coreMiddleware = [
        \TurFramework\Session\Middleware\StartSession::class,
        \TurFramework\Http\Middleware\VerifyCsrfToken::class
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [];


    public function __construct(Router $router)
    {
        $this->router = $router;

        $this->syncMiddlewareToRouter();
    }
    /**
     * Handle an incoming HTTP request.
     *
     * @param  \TurFramework\Http\Request  $request
     */
    public function handle($request)
    {
        try {
            $this->sendRequestThroughRouter($request);
            //code...
        } catch (\Throwable $th) {
            ExceptionHandler::customExceptionHandler($th, $request);
        }
    }

    public function sendRequestThroughRouter($request)
    {
        return  $this->router->resolve($request);
    }


    /**
     * Sync the current state of the middleware to the router.
     *
     * @return void
     */
    protected function syncMiddlewareToRouter()
    {
        $this->router->setGlobalMiddleware(array_merge($this->coreMiddleware, $this->middleware));

        foreach ($this->routeMiddleware as $key => $middleware) {
            $this->router->setRouteMiddleware($key, $middleware);
        }
    }
}
