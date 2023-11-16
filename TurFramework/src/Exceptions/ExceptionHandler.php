<?php

namespace TurFramework\src\Exceptions;

interface ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render();
}
