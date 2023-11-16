<?php

namespace TurFramework\src\Router;

use TurFramework\src\Exceptions\BaseException;

class RouteNotFoundException extends BaseException
{
    protected $message = 'page not found';
    protected $code = 404;
}
