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
        Auth::attempt(['email' => $request->email, 'password' => $request->password]);
    }

    public function logout()
    {
    }
}
