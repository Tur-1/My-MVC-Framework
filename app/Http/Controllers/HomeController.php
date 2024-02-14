<?php

namespace App\Http\Controllers;

use TurFramework\Facades\View;
use TurFramework\Http\Request;
use App\Services\ExampleServiceInterface;

class HomeController extends Controller
{

    public function index(Request $request, ExampleServiceInterface $exampleService)
    {

        return View::make('pages.HomePage');
    }
}
