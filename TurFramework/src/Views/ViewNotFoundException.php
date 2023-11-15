<?php

namespace TurFramework\src\Views;

class ViewNotFoundException extends \Exception
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;

        $this->render();
        exit();
    }

    public function render()
    {
        echo $this->message;
    }
}
