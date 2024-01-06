<?php

namespace App\Http\Controllers;

use App\Services\ExampleService;
use TurFramework\Core\Http\Request;

class HomeController extends Controller
{

    public function index(Request $request, ExampleService $exampleService)
    {

        return view('pages.HomePage');
    }

    public function user(Request $request, $id)
    {
        return $id;
    }
}
