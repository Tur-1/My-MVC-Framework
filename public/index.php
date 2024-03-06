<?php

use TurFramework\Http\Request;

/**
 * Require the composer autoload File.
 */

require __DIR__ . '/../vendor/autoload.php';


/**
 * Bootstrap the Application.
 */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->dispatch(new Request);
