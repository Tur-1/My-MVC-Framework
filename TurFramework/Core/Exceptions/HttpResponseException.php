<?php

namespace TurFramework\Core\Exceptions;

class HttpResponseException extends \Exception
{
    protected $message;
    protected $code;


    public function getMessageForStatusCode($statusCode)
    {
        if (empty($this->message)) {
            return match ($statusCode) {
                400 => 'Bad Request',
                401 => 'Unauthorized',
                402 => 'Payment Required',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                500 => 'Internal Server Error',
                429 => 'Too Many Requests',
                419 => 'Page Expired',
                default => 'Unknown Status'
            };
        } else {
            return $this->message;
        }
    }
}
