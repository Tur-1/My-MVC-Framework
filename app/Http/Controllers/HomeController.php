<?php

namespace App\Http\Controllers;

use TurFramework\Http\Request;
use App\Services\ExampleServiceInterface;
use TurFramework\Facades\Auth;

class HomeController extends Controller
{

    public function index(Request $request, ExampleServiceInterface $exampleService)
    {

        return view('pages.homePage');
    }
}
