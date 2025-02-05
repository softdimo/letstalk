<?php

namespace App\Http\Controllers\comunes;

use App\Http\Controllers\Controller;

class ComunController extends Controller
{
    public function aboutUs()
    {
        return view('layouts.about_us');
    }

    public function services()
    {
        return view('layouts.services');
    }
}
