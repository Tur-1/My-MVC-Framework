<?php

namespace App\Http\Controllers;

use TurFramework\Facades\Auth;
use TurFramework\Http\Request;
use TurFramework\Validation\ValidationException;

class AdminController extends Controller
{

    public function dashboard(Request $request)
    {

        return view('pages.Admin.dashboard');
    }

    public function login()
    {

        return view('pages.Admin.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);


        if (!Auth::guard('admins')->attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages(
                $request->only('email', 'password'),
                [
                    'email' => 'These credentials do not match our records.'
                ]
            );
        }


        return redirect()->to(route('admin.dashboard'))->with('success', "You're logged in!");
    }

    public function logout(Request $request)
    {

        Auth::guard('admins')->logout();

        return redirect()->to(route('admin.login'));
    }
}
