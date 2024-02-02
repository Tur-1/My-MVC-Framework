<?php

namespace App\Services;

use App\Models\User;


class ExampleService implements ExampleServiceInterface
{

    public function example()
    {
        User::all();
        User::all();
    }
}
