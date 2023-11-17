<?php

namespace App\Http\Controllers;

class HomeController
{
    public function index()
    {
        return view('pages.HomePage.index', [
            'name' => 'turki',
        ]);
    }
}
