<?php

namespace App\Http\Controllers;

use TurFramework\Core\Http\Request;

class HomeController
{
    public function index()
    {


        return view('pages.HomePage');
    }

    public function store(Request $request)
    {
        echo "store";
    }

    public function delete(Request $request)
    {
        echo "delete";
    }
}
