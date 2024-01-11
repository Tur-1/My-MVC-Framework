<?php

namespace TurFramework\src\Router\Exceptions;

use TurFramework\src\Exceptions\HttpResponseException;

class RouteNotFoundException extends HttpResponseException
{

    protected $code = 404;
}
