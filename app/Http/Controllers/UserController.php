<?php

namespace App\Http\Controllers;

use App\Exceptions\UserExeption;
use App\Models\User;
use TurFramework\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{

    public function index(Request $request)
    {

        $users = User::query()->get();
        return view('pages.Users.list')->with('users', $users);
    }
    public function profile()
    {

        return view('pages.Users.profile');
    }
    public function create()
    {


        return view('pages.Users.create');
    }

    public function store(StoreUserRequest $request)
    {

        $validatedRequest = $request->validated();

        $user = User::query()->create($validatedRequest);

        return redirect()->to(route('users.list'))
            ->with('success', 'New User was added successfully.');
    }
    public function edit($id)
    {

        $user = User::query()->find($id);
        if (is_null($user)) {
            throw UserExeption::notFound();
        }

        return view('pages.Users.edit')->with('user', $user);
    }

    public function update(UpdateUserRequest $request, $id)
    {

        $validatedRequest = $request->validated();

        $user = User::query()->where('id', $id)->first();

        $user->update($validatedRequest);

        return redirect()->back()
            ->with('success', 'User was updated successfully.');
    }

    public function delete($id)
    {
        $user = User::query()->where('id', $id)->first();

        if (is_null($user)) return;

        $user->delete();



        return redirect()->back()
            ->with('success', 'User was deleted successfully.');
    }
}
