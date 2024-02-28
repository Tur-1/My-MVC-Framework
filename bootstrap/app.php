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
$app = new \TurFramework\Application\Application();

$kernel = $app->make(\App\Http\Kernel::class);

$kernel->handle(new Request);
