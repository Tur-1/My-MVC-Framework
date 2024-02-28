<?php

namespace App\Http\Controllers\Auth;

use TurFramework\Facades\Auth;
use TurFramework\Http\Request;
use App\Http\Requests\LoginRequest;

class AuthenticatedController
{
    public function index()
    {

        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();

        return redirect()->to(route('dashboard'))->with('success', "You're logged in!");
    }

    public function logout(Request $request)
    {

        Auth::logout();

        return redirect()->to('/');
    }
}
