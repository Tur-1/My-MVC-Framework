<?php


require_once __DIR__ . '/../vendor/autoload.php';


require_once base_path('/routes/web.php');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

use src\App;

$app = new App();


$app->run();