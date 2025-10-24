<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function contact($title='Contact') {
        return view('contact')->with('title', 'Contact');
    }
}