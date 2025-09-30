<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\offers;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    //
    public function index()
    {
        $offers = offers::all()->shuffle();
        $categories = Categorie::all();
        if (request()->path() === '/') {
            return view("welcome", compact("offers", 'categories'));
        } else {
            return view("store", compact("offers", 'categories'));
        }
    }
}
