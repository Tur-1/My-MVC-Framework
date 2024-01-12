<?php

namespace TurFramework\Application;


abstract class ServiceProvider
{
    /**
     * The application instance.
     *
     * @var \TurFramework\Application\Application
     */
    protected $app;

    /**
     * Create a new service provider instance.
     *
     * @param \TurFramework\Application\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
