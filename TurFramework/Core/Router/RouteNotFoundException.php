<?php

namespace TurFramework\Core\Router;

use TurFramework\Core\Exceptions\HttpResponseException;

class RouteNotFoundException extends HttpResponseException
{

    protected $code = 404;
}
