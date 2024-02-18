<?php

namespace App\Http\Controllers\Auth;

class AuthenticatedController
{
    public function index()
    {

        return view('auth.login');
    }

    public function store()
    {
    }

    public function logout()
    {
    }
}
