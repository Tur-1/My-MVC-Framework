<?php

namespace App\Http\Controllers;

use App\Models\Product;

use App\Models\Brand;
use TurFramework\Http\Request;
use App\Services\ExampleServiceInterface;

class HomeController extends Controller
{

    public function index(Request $request, ExampleServiceInterface $exampleService)
    {
        dd(Product::connection('mysql')->all());

        return view('pages.HomePage');
    }
    public function edit(Request $request, $id)
    {
        echo $id;
    }
    public function user(Request $request, ExampleServiceInterface $exampleService, $id, $name)
    {
    }
}
