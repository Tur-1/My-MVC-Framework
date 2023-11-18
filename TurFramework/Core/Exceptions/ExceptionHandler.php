<?php

namespace TurFramework\Core\Exceptions;

use Error;
use Exception;

class ExceptionHandler
{
    public static function registerExceptions()
    {
        set_exception_handler([__CLASS__, 'customExceptionHandler']);
    }

    public static function customExceptionHandler($exception)
    {
        if ($exception instanceof HttpResponseException) {
            self::handleHttpResponseException($exception);
        }

        if ($exception instanceof Exception || $exception instanceof Error) {
            DefaultExceptionHandler::handle($exception);
        }
    }

    private static function handleHttpResponseException($exception)
    {
        $message = $exception->getMessage();
        $code = $exception->getCode();
        http_response_code($code);
        include 'views/HttpResponseExceptionView.php';
        exit();
    }

    private static function handleDefaultException($exception)
    {
        $errorData = [];

        $message = $exception->getMessage();
        $multipleMessages = method_exists($exception, 'getMultipleMessages') ? $exception->getMultipleMessages() : [];

        $className = explode('\\', get_class($exception));
        $exceptionClass = end($className);

        $trace = $exception->getTrace();

        foreach ($trace as $key => $value) {
            if (isset($value['file'])) {
                $errorData[] = [
                     'error_file' => $value['file'],
                     'error_line' => $value['line'],
                    ];
            }
        }

        include base_path('TurFramework/src/Exceptions/views/ExceptionView.php');
        exit();
    }
}
