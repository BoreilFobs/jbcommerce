<?php

namespace App\Http\Controllers;

use App\Models\wishes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishesController extends Controller
{
    /**
     * Display the user's wishlist
     */
    public function index($id)
    {
        // Check if the authenticated user is accessing their own wishlist
        if (!Auth::check() || Auth::id() != $id) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à votre liste de souhaits.');
        }

        $wishes = wishes::where('user_id', $id)->get();
        return view('wishlist', compact("wishes"));
    }

    /**
     * Add item to wishlist
     */
    public function store($Oid, $Uid)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour ajouter des articles à votre liste de souhaits.');
        }

        // Check if user is accessing their own wishlist
        if (Auth::id() != $Uid) {
            return redirect()->back()->with('error', 'Action non autorisée.');
        }

        // Check if item already exists in wishlist
        $existingWish = wishes::where('user_id', $Uid)
                             ->where('offer_id', $Oid)
                             ->first();

        if ($existingWish) {
            return redirect('/wish-list/' . $Uid)->with('info', 'Cet article est déjà dans votre liste de souhaits.');
        }

        wishes::create([
            'user_id' => $Uid,
            'offer_id' => $Oid,
        ]);
        
        return redirect('/wish-list/' . $Uid)->with('success', 'Article ajouté à votre liste de souhaits.');
    }

    /**
     * Delete item from wishlist
     */
    public function delete($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour gérer votre liste de souhaits.');
        }

        $wish = wishes::findOrFail($id);
        
        // Check if the wishlist item belongs to the authenticated user
        if ($wish->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Action non autorisée.');
        }

        $wish->delete();
        return redirect()->back()->with('success', 'Article retiré de votre liste de souhaits.');
    }

    /**
     * Redirect to login if not authenticated
     */
    public function redirect()
    {
        return redirect()->route("login")->with('error', 'Veuillez vous connecter pour accéder à votre liste de souhaits.');
    }
}
