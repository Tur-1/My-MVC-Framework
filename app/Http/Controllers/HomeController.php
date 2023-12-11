<?php

namespace App\Http\Controllers;

use TurFramework\Core\Facades\Request;

class HomeController
{
    public function index(Request $request)
    {


        return view('pages.HomePage')->with('name', 'turki');
    }

    public function about(Request $request)
    {

        return view('pages.aboutPage')->with('message', 'you are the greatest developer in the whole world !');
    }
}
