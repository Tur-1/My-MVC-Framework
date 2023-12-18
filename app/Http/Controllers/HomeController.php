<?php

namespace App\Http\Controllers;



class HomeController
{
    public function index()
    {

        return view('pages.HomePage');
    }

    public function about()
    {
        return view('pages.aboutPage');
    }
}
