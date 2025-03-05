<?php

namespace App\Http\Controllers;

use App\Models\offers;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    //
    public function index()
    {
        $offers = offers::all()->shuffle();
        return view("welcome", compact("offers"));
    }
}
