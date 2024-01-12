<?php

namespace TurFramework\Router\Exceptions;

use TurFramework\Exceptions\HttpResponseException;

class RouteNotFoundException extends HttpResponseException
{

    protected $code = 404;
}
