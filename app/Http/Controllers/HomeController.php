<?php

namespace App\Http\Controllers;

use App\actions\Serv;
use TurFramework\Core\Http\Request;

class HomeController extends Controller
{

    public function index(Request $request, Serv $serv)
    {

        dd($serv->gets(), $request->getHost());
        return view('pages.HomePage');
    }

    public function about(Request $request, Serv $serv, $id, $name)
    {

        dd($serv->gets(), $request->getHost(), $id, $name);
        return view('pages.aboutPage');
    }
}
