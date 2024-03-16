<?php

namespace App\Http\Controllers;

use App\Models\Admin;
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
    public function register()
    {

        return view('pages.Admin.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);


        if (!Auth::guard('admins')->attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages(
                [
                    'email' => 'These credentials do not match our records.'
                ]
            );
        }


        return redirect()->to(route('admin.dashboard'))->with('success', "You're logged in!");
    }

    public function registerAdmin(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:' . Admin::class],
            'password' => ['required', 'confirmed'],
        ]);

        $admin = Admin::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);



        Auth::guard('admins')->login($admin);

        return redirect()->to(route('admin.dashboard'))->with('success', "You're logged in!");
    }

    public function logout(Request $request)
    {

        Auth::guard('admins')->logout();

        return redirect()->to(route('admin.login'));
    }
}
