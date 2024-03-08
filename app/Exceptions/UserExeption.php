<?php

namespace App\Exceptions;

use TurFramework\Http\HttpException;

class UserExeption extends HttpException
{
    protected $message;

    protected $code = 404;
    public static function notFound()
    {
        return new self();
    }
}
