<?php

namespace App\Http\Controllers;

use App\Services\ExampleService;
use TurFramework\Core\Http\Request;
use App\Services\ExampleServiceInterface;

class HomeController extends Controller
{

    public function index(Request $request, ExampleServiceInterface $exampleService)
    {


        return view('pages.HomePage');
    }

    public function user(Request $request, $id)
    {
        return $id;
    }
}
