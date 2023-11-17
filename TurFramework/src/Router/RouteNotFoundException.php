<?php

namespace TurFramework\src\Router;

use TurFramework\src\Exceptions\HttpResponseException;

class RouteNotFoundException extends HttpResponseException
{
    protected $message = 'page not found';
    protected $code = 404;
}
