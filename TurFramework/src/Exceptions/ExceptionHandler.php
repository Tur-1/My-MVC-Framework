<?php

namespace TurFramework\Exceptions;

use ErrorException;
use TurFramework\Http\HttpException;
use TurFramework\Validation\ValidationException;

class ExceptionHandler
{


    public static function registerExceptions()
    {
        if (env('APP_DEBUG') == 'false') {
            self::customExceptionHandler(new HttpException());
        }

        set_error_handler([self::class,  'errorHandler']);
        set_exception_handler([self::class,  'customExceptionHandler']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }
    public static function customExceptionHandler($exception)
    {

        return  match (true) {
            $exception instanceof HttpException => self::handleHttpException($exception),
            $exception instanceof ValidationException => self::handleValidationExceptionResponse($exception),
            default => self::getDefaultExceptionHandler($exception)
        };
    }

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public static function handleShutdown()
    {

        if (!is_null($error = error_get_last()) && static::isFatal($error['type'])) {

            throw new ErrorException($error['message'], 0, $error['type'], $error['file'],  $error['line']);
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


    private static function handleValidationExceptionResponse($exception)
    {
        return redirect()
            ->to($exception->redirectTo ?? request()->previousUrl())
            ->withOldValues($exception->getOldValues())
            ->withErrors($exception->errors());
    }
    private static function getDefaultExceptionHandler($exception)
    {
    
        ob_end_clean();
        [
            $errorData,
            $primary_message,
            $secondary_message,
            $multipleMessages,
            $className
        ] = DefaultExceptionHandler::handle($exception);

        ob_clean();

        
        ob_start();

        include 'views/ReportExceptionView.php';

        $errorPageContent = ob_get_clean();
     
        echo $errorPageContent;
       
        exit();
    }

    private static function handleHttpException($exception)
    {

        $code = $exception->getCode();
        $message = $exception->getMessageForStatusCode($code);

        http_response_code($code);

        ob_start();
        require 'views/HttpResponseExceptionView.php';
        $output = ob_get_clean();
        echo $output;
        exit();
    }
}
