<?php

namespace src;

use src\Http\Request;
use src\Router\Route;
use src\Http\Response;

class App
{

    private $route;

    public function __construct()
    {


        $this->route =  new Route(new Request, new Response);
        $this->route->loadAllRoutesFiles();
    }
    public function run(): void
    {

        $this->route->reslove();
    }
}
