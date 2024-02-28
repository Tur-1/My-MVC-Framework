<?php

namespace App\Http\Controllers\Auth;

use TurFramework\Http\Request;

class RegisterController
{
    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
    }
}
