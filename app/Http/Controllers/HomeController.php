<?php

namespace App\Http\Controllers;

use App\Models\User;
use TurFramework\Facades\View;
use TurFramework\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Services\ExampleServiceInterface;

class HomeController extends Controller
{

    public function index(Request $request, ExampleServiceInterface $exampleService)
    {


        return View::make('pages.HomePage');
    }
    public function store(StoreUserRequest $request)
    {

        $request->validated();

        redirect()->back()->with('message', 'was sent succssfully');
    }
}
