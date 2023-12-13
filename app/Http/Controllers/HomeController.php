<?php

namespace App\Http\Controllers;

use TurFramework\Core\Http\Request;

class HomeController
{
    public function index()
    {

        return view('pages.HomePage')->with('name', 'turki');
    }

    public function about()
    {
        return view('pages.aboutPage')->with('message', 'you are the greatest developer in the whole world !');
    }
}
