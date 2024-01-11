<?php

namespace TurFramework\Core\Application;


abstract class ServiceProvider
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    abstract public function register();
}
