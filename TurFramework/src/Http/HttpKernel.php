<?php

namespace TurFramework\Http;

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


    public function __construct()
    {

        $this->router = app('router');

        $this->syncMiddlewareToRouter();
    }
    /**
     * Handle an incoming HTTP request.
     *
     * @param  \TurFramework\Http\Request  $request
     */
    public function handle($request)
    {
        $this->sendRequestThroughRouter($request);
    }

    public function sendRequestThroughRouter($request)
    {
        $this->router->resolve($request);
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
