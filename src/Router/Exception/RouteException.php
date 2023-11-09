<?php

namespace src\Router\Exception;

use \Exception;

class RouteException extends Exception
{
    public function __construct(string $message, int $statusCode = 404)
    {

        echo $message;
        die();
    }
}
