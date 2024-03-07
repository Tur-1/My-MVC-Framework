<?php

namespace App\Exceptions;

use TurFramework\Exceptions\HttpException;

class UserExeption extends HttpException
{
    protected $message;

    protected $code = 404;
    public static function notFound()
    {
        return new self();
    }
}
