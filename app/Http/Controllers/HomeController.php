<?php

namespace App\Http\Controllers;

use TurFramework\Core\Http\Request;

class HomeController
{
    public function index()
    {
        return view('pages.HomePage', [
            'name' => 'turki',
        ]);
    }

    public function form(Request $request)
    {
        dd($request);
        redirect('/products')->with(['message' => 'thean lsd', 'name' => 'turki']);
    }
}
