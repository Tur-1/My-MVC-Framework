<?php


use Dotenv\Dotenv;
use TurFramework\Http\Request;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


/*
|--------------------------------------------------------------------------
| start The Application
|--------------------------------------------------------------------------
*/


\TurFramework\Application\Application::create()->dispatch(new Request);
