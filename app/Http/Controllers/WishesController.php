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
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à vos favoris.');
        }

        $wishes = wishes::where("user_id", Auth::id())->get();
        return view("wishlist", compact('wishes'));
    }

    /**
     * Add item to wishlist
     */
    public function store($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour ajouter aux favoris.');
        }

        $userId = Auth::id();
        $offerId = $id;

        // Check if item already exists in wishlist
        $existingWish = wishes::where('user_id', $userId)
                             ->where('offer_id', $offerId)
                             ->first();

        if ($existingWish) {
            return redirect()->route('wishlist.index')->with('info', 'Cet article est déjà dans vos favoris.');
        }

        wishes::create([
            'offer_id' => $offerId,
            'user_id' => $userId,
        ]);
        
        return redirect()->route('wishlist.index')->with('success', 'Article ajouté aux favoris avec succès.');
    }

    /**
     * Delete item from wishlist
     */
    public function delete($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour gérer vos favoris.');
        }

        $wish = wishes::findOrFail($id);
        
        // Check if the wishlist item belongs to the authenticated user
        if ($wish->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Action non autorisée.');
        }

        $wish->delete();
        return redirect()->back()->with('success', 'Article retiré des favoris.');
    }
}
