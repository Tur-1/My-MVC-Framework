<?php

namespace src;

use src\Http\Request;
use src\Router\Route;
use src\Http\Response;

class App
{

    public function run(): void
    {

        $route = new Route(new Request, new Response);

        $route->reslove();
    }
}
