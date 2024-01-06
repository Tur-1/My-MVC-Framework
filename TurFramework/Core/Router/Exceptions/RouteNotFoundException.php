<?php

namespace TurFramework\Core\Router\Exceptions;

use TurFramework\Core\Exceptions\HttpResponseException;

class RouteNotFoundException extends HttpResponseException
{

    protected $code = 404;
}
