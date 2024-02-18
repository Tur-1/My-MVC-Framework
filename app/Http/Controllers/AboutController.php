<?php

namespace App\Http\Controllers;

use TurFramework\Http\Request;

class AboutController extends Controller
{

    public function index(Request $request)
    {
        return view('pages.aboutPage');
    }
}
