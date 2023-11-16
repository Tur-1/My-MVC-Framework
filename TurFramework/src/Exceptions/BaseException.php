<?php

namespace TurFramework\src\Exceptions;

class BaseException extends \Exception implements ExceptionHandler
{
    protected $message;
    protected $code;

    public function __construct($message = '', $code = 0, \Throwable $previous = null)
    {
        $this->message = $this->message ?? $message;
        $this->code = $this->code ?? $code;

        parent::__construct($this->message, $this->code, $previous);
        http_response_code($this->code);

        $this->render();
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render()
    {
        $message = $this->message;
        $code = $this->code;

        include base_path('TurFramework/src/Exceptions/views/layout.php');
    }
}
