<?php

namespace TurFramework\src\Views;

use Exception;

class ViewNotFoundException extends Exception
{
    protected $message = '';
    protected $code = 404;

    public $multipleMessages = [];

    public function __construct($viewPath)
    {
        $this->message = 'View['.$viewPath.'] not found.';
        $this->multipleMessages = [
            'View['.$viewPath.'] not found.',
            'Are you sure the view exists ?',
        ];
    }

    public function getMultipleMessages()
    {
        return $this->multipleMessages;
    }
}
