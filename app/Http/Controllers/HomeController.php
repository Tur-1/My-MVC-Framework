<?php

namespace App\Http\Controllers;

use TurFramework\Http\Request;
use App\Services\ExampleServiceInterface;

class HomeController extends Controller
{

    public function index(Request $request, ExampleServiceInterface $exampleService)
    {
        // dd(session()->all());
        return view('pages.HomePage');
    }
}
