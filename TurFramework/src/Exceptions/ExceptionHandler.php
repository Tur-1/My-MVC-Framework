<?php

namespace TurFramework\Exceptions;

use ErrorException;
use TurFramework\Http\HttpException;
use TurFramework\Http\Request;
use TurFramework\Support\Arr;
use TurFramework\Validation\ValidationException;
use TurFramework\views\ViewFactory;

class ExceptionHandler
{

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    public static function registerExceptions($app)
    {
        if ($app->isDebugModeDisabled()) {
            ini_set('display_errors', 'Off');
        }

        set_error_handler([self::class,  'errorHandler']);
        set_exception_handler([self::class,  'customExceptionHandler']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }
    public static function customExceptionHandler($exception, $request = null)
    {

        return  match (true) {
            $exception instanceof HttpException => self::handleHttpException($exception),
            $exception instanceof ValidationException => (new self)->handleValidationExceptionResponse($exception, $request),
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


    private function handleValidationExceptionResponse($exception, $request)
    {
        return redirect()
            ->to($exception->redirectTo ?? $request->previousUrl())
            ->withOldValues(Arr::except($request->all(), $this->dontFlash))
            ->withErrors($exception->errors());
    }
    private static function getDefaultExceptionHandler($exception)
    {
        ob_get_clean();
        [
            $errorData,
            $primary_message,
            $secondary_message,
            $multipleMessages,
            $className
        ] = DefaultExceptionHandler::handle($exception);


        if (ob_get_length()) {
            ob_clean();
        }

        ViewFactory::makeView(dirname(__DIR__) . '/Exceptions/views/ReportExceptionView.php')->with(get_defined_vars());

        exit();
    }

    private static function handleHttpException($exception)
    {

        ob_get_clean();

        $code = $exception->getCode();
        $message = $exception->getMessageForStatusCode($code);

        http_response_code($code);

        if (ob_get_length()) {
            ob_clean();
        }


        ViewFactory::makeView(dirname(__DIR__) . '/Exceptions/views/HttpResponseExceptionView.php')->with(get_defined_vars());

        exit();
    }
}
