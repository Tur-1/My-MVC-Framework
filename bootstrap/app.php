<?php


use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

/*
|--------------------------------------------------------------------------
| start The Application
|--------------------------------------------------------------------------
*/


$app = new \TurFramework\Application\Application();

$app->bind(\App\Http\Kernel::class);

return $app;
