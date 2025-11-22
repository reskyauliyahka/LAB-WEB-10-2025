<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DefinitionController extends Controller
{
    public function definition(){

        return view('definition')-> with('title', 'definition');    
    }
}
