<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Color;
use TurFramework\Facades\Auth;
use TurFramework\Http\Request;

class RegisterController
{
    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:' . User::class],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);


        Auth::login($user);

        return redirect()->to(route('dashboard'))->with('success');
    }
}
