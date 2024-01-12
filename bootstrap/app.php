<?php

//Starting the session will be the first we do.
session_start();

use Dotenv\Dotenv;
use TurFramework\Application\Application;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


/*
|--------------------------------------------------------------------------
| start The Application
|--------------------------------------------------------------------------
*/

Application::start();
