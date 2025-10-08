<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\offers;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $query = request('search');
        $categories = Categorie::all();
        
        if ($query) {
            $offers = offers::where('name', 'LIKE', "%{$query}%")
                          ->orWhere('category', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%")
                          ->get();
            return view("store", compact("offers", 'categories', 'query'));
        }

        $offers = offers::all()->shuffle();
        
        if (request()->path() === '/') {
            return view("welcome", compact("offers", 'categories'));
        } else {
            return view("store", compact("offers", 'categories'));
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        $categories = Categorie::all();
        
        $offers = offers::where('name', 'LIKE', "%{$query}%")
                      ->orWhere('category', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->get();

        return view('store', compact('offers', 'categories', 'query'));
    }

    // New arrivals filter (offers < 1 month old)
    public function newArrivals()
    {
        $oneMonthAgo = now()->subMonth();
        $offers = offers::where('created_at', '>=', $oneMonthAgo)->orderBy('created_at', 'desc')->get();
        return response()->json($offers);
    }
}
