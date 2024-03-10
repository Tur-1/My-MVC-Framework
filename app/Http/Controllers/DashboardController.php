<?php

namespace App\Http\Controllers;

use TurFramework\Http\Request;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
      

        return view('pages.dashboard');
    }
}
