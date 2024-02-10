<?php

namespace TurFramework\Views;

use Exception;

class ViewException extends Exception
{
    protected $message = '';
    protected $code = 404;

    public $multipleMessages = [];

    public function __construct($viewPath)
    {
        $this->message = 'View [  ' . $viewPath . '  ] not found.';
        $this->multipleMessages = [
            'View[ ' . $viewPath . ' ] not found.',
            'Are you sure the view exists ?',
        ];
    }

    public static function notFound($viewPath)
    {
        return new self($viewPath);
    }
    public function getMultipleMessages()
    {
        return $this->multipleMessages;
    }
}
