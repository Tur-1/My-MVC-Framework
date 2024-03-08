<?php

namespace TurFramework\Router\Exceptions;

use TurFramework\Http\HttpException;

class RouteNotFoundException extends HttpException
{

    protected $code = 404;
}
