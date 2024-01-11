<?php

namespace App\Http\Controllers;

use TurFramework\Core\Http\Request;

class AboutController extends Controller
{

    public function index(Request $request)
    {
        return view('pages.aboutPage');
    }
}
