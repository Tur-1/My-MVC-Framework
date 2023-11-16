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
        foreach (debug_backtrace() as $key => $value) {
            if (isset($value['file'])) {
                $errorData[] = [
                     'error_file' => $value['file'],
                     'error_line' => $value['line'],
                    ];
            }
        }

        ob_start(); // Start output buffering
        include base_path('TurFramework/src/Exceptions/views/invalidArgument.php');  // Include HTML error page
        $errorOutput = ob_get_clean(); // Get buffered HTML content
        echo $errorOutput; // Display error output
    }
}
