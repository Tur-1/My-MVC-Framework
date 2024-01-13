<?php

namespace App\Services;

use App\Services\ServiceCop;

class ExampleService implements ExampleServiceInterface
{

    public $serviceCop;
    public function __construct(ServiceCop $serviceCop)
    {
        $this->serviceCop = $serviceCop->gets();
    }
    /**
     * example 
     */
    public function example()
    {
        return $this->serviceCop;
    }
}
