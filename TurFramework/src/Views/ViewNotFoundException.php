<?php

namespace TurFramework\src\Views;

use TurFramework\src\Exceptions\BaseException;

class ViewNotFoundException extends BaseException
{
    public function render()
    {
        $errorData = [];

        $errorMessage = $this->getMessage();
        $exceptionClass = 'ViewNotFoundException';
        $trace = debug_backtrace();
        // array_shift($trace);
        foreach (debug_backtrace() as $key => $value) {
            if (isset($value['file'])) {
                $errorData[] = [
                     'error_file' => $value['file'],
                     'error_line' => $value['line'],
                    ];
            }
        }

        include base_path('TurFramework/src/Exceptions/views/invalidArgument.php');
        exit(); // Include HTML error page
    }
}
