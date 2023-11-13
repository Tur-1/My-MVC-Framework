<?php

namespace src\Views;

class ViewNotFoundException extends \Exception
{


    public $message;
    public function __construct($message)
    {
        parent::__construct($message);

        $this->message = $message;

        $this->render();
    }

    public function render()
    {
        return view('Errors.404', ['message' => $this->message]);
    }
}
