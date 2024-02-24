<?php

namespace App\Http\Controllers\Auth;

use TurFramework\Facades\Auth;
use TurFramework\Http\Request;

class AuthenticatedController
{
    public function index()
    {

        return view('auth.login');
    }

    public function store(Request $request)
    {
    }

    public function logout()
    {
    }
}
