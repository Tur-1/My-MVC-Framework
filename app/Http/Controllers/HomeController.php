<?php

namespace App\Http\Controllers;

use TurFramework\Core\Http\Request;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        return view('pages.HomePage');
    }

    public function user(Request $request, $id)
    {
        return $id;
    }
}
