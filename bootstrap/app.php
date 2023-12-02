<?php

//Starting the session will be the first we do.
session_start();

use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
