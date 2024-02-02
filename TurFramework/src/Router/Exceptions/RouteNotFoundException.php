<?php

namespace TurFramework\Router\Exceptions;

use TurFramework\Exceptions\HttpException;

class RouteNotFoundException extends HttpException
{

    protected $code = 404;
}
