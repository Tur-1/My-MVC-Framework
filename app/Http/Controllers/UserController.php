<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{

    public function index()
    {
        $users = User::query()->get();

        return view('pages.Users.list', ['users' => $users]);
    }

    public function create()
    {

        return view('pages.Users.create');
    }

    public function store(StoreUserRequest $request)
    {

        $validatedRequest = $request->validated();

        User::query()->create($validatedRequest);

        return redirect()->to(route('usersList'))
            ->with('success', 'New User was added successfully.');
    }
    public function edit($id)
    {


        $user = User::query()->find($id);

        return view('pages.Users.edit')->with('user', $user);
    }

    public function update(UpdateUserRequest $request, $id)
    {

        $validatedRequest = $request->validated();

        User::query()->where('id', $id)->update($validatedRequest);

        return redirect()->back()
            ->with('success', 'User was updated successfully.');
    }

    public function delete($id)
    {

        User::query()->delete($id);

        return redirect()->back()
            ->with('success', 'User was deleted successfully.');
    }
}
