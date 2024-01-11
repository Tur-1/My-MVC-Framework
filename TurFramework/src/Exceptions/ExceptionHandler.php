<?php

namespace TurFramework\src\Exceptions;

use Error;
use DateError;
use Exception;
use Throwable;
use TypeError;
use ParseError;
use CompileError;
use ErrorException;

class ExceptionHandler
{
    private static $Exceptions = [
        DateError::class,
        CompileError::class,
        Exception::class,
        Error::class,
        ErrorException::class,
        Throwable::class,
        ParseError::class,
        TypeError::class,

    ];


    public static function registerExceptions()
    {

        set_error_handler([self::class,  'errorHandler']);
        set_exception_handler([self::class,  'customExceptionHandler']);
    }


    public static function errorHandler($severity, $message, $file, $line)
    {

        if (error_reporting() && $severity) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        }
    }
    public static function customExceptionHandler($exception)
    {


        if ($exception instanceof HttpResponseException) {
            self::handleHttpResponseException($exception);
        }

        self::getDefaultExceptionHandler($exception);
    }


    private static function getDefaultExceptionHandler($exception)
    {

        foreach (self::$Exceptions as $key => $class) {
            if ($exception instanceof $class) {
                ob_clean();
                DefaultExceptionHandler::handle($exception);
                break;
            }
        }
    }
    private static function handleHttpResponseException($exception)
    {

        $code = $exception->getCode();
        $message = $exception->getMessageForStatusCode($code);

        http_response_code($code);

        ob_start();
        include 'views/HttpResponseExceptionView.php';
        $output = ob_get_clean();
        echo $output;
        exit();
    }
}
