<?php

namespace TurFramework\Core\Exceptions;

use Error;
use Exception;
use Throwable;
use ErrorException;


class ExceptionHandler
{
    private static $debugMode; // Set the default value here or retrieve it from your configuration



    public static function registerExceptions()
    {
       
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
