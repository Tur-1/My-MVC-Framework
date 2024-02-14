<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{

    public function index()
    {
        return view('pages.HomePage');
    }
    public function store(StoreUserRequest $request)
    {

        $validatedRequest = $request->validated();

        User::query()->create($validatedRequest);

        return redirect()->back();
    }
}
