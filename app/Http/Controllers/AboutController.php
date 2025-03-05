<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    //
    public function index()
    {

        return view('about');
    }
    public function team(){
        return view("team.index");
    }
}
