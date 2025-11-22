<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CulinaryController extends Controller
{
    public function culinary($title='Culinary') {
        return view('culinary')->with('title', 'Culinary');
    }
}
