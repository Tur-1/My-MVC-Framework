<?php

namespace App\Http\Controllers;

use App\Models\User;
use TurFramework\Http\Request;
use App\Services\ExampleServiceInterface;
use TurFramework\Facades\View;

class HomeController extends Controller
{

    public function index(Request $request, ExampleServiceInterface $exampleService)
    {


        return View::make('pages.HomePage');
    }
    public function store(Request $request)
    {

        redirect()->back()->with('message', 'was sent succssfully');
    }
}
