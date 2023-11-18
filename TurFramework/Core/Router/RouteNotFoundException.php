<?php

namespace TurFramework\Core\Router;

use TurFramework\Core\Exceptions\HttpResponseException;

class RouteNotFoundException extends HttpResponseException
{
    protected $message = 'page not found';
    protected $code = 404;
}
