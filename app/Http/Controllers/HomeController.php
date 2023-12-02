<?php

namespace App\Http\Controllers;

use TurFramework\Core\Http\Request;

class HomeController
{
    public function index(Request $request)
    {

        dd(route('aboutPage', ['name' => 'turki', 'adsge' => '23']));

        return view('pages.HomePage');
    }

    public function about(Request $request)
    {
        $param = $request->has('name');

        dd($param);
        return view('pages.AboutPage');
    }
}
