<?php

namespace TurFramework\Exceptions;

use Error;
use DateError;
use Exception;
use Throwable;
use TypeError;
use ParseError;
use CompileError;
use ErrorException;
use RuntimeException;

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
        RuntimeException::class,
    ];


    public static function registerExceptions()
    {


        if (config('app.debug') == 'true') {
            set_error_handler([self::class,  'errorHandler']);
            set_exception_handler([self::class,  'customExceptionHandler']);
            register_shutdown_function([self::class, 'handleShutdown']);
        } else {

            try {
                throw new HttpResponseException(code: 500);
            } catch (HttpResponseException $ex) {
                self::handleHttpResponseException($ex);
            }
        }
    }
    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public static function handleShutdown()
    {

        if (!is_null($error = error_get_last()) && static::isFatal($error['type'])) {

            try {
                throw new ErrorException($error['message'], 0, $error['type'], $error['file'],  $error['line']);
            } catch (\ErrorException $exception) {

                self::getDefaultExceptionHandler($exception);
            }
        }
    }

    /**
     * Determine if the error type is fatal.
     *
     * @param  int  $type
     * @return bool
     */
    protected static function isFatal($type)
    {
        return in_array($type, [E_COMPILE_ERROR, E_CORE_ERROR, E_ERROR, E_PARSE]);
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
