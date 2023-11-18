<?php

namespace TurFramework\Core\Exceptions;

use Error;
use Exception;
use Throwable;
use ErrorException;

class ExceptionHandler
{
    public static function registerExceptions()
    {
        error_reporting(-1);
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
        });

        set_exception_handler([self::class,  'customExceptionHandler']);
    }

    public static function customExceptionHandler($exception)
    {
        if ($exception instanceof HttpResponseException) {
            self::handleHttpResponseException($exception);
        }

        if ($exception instanceof Exception || $exception instanceof Error || $exception instanceof ErrorException || $exception instanceof Throwable) {
            ob_clean();
            DefaultExceptionHandler::handle($exception);
        }
    }

    private static function handleHttpResponseException($exception)
    {
        $message = $exception->getMessage();
        $code = $exception->getCode();
        http_response_code($code);

        ob_start();
        include 'views/HttpResponseExceptionView.php';
        $output = ob_get_clean();
        echo $output;
        exit();
    }
}
