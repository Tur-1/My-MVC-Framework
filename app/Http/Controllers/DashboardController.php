<?php

namespace App\Http\Controllers;

use TurFramework\Http\Request;
use App\Services\ExampleServiceInterface;

class DashboardController extends Controller
{

    public function index(Request $request, ExampleServiceInterface $exampleService)
    {

        return view('pages.dashboard');
    }
}
