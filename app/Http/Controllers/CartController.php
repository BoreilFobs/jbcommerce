<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the user's cart
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à votre panier.');
        }

        $carts = Cart::where("user_id", Auth::id())->get();
        return view("cart", compact('carts'));
    }

    /**
     * Add item to cart
     */
    public function store($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour ajouter des articles au panier.');
        }

        $userId = Auth::id();
        $offerId = $id;

        // Check if item already exists in cart
        $existingCart = Cart::where('user_id', $userId)
                           ->where('offer_id', $offerId)
                           ->first();

        if ($existingCart) {
            // Update quantity if item exists
            $existingCart->quantity += 1;
            $existingCart->save();
            return redirect()->route('cart.index')->with('success', 'Quantité mise à jour dans le panier.');
        } else {
            // Create new cart item
            Cart::create([
                'offer_id' => $offerId,
                'user_id' => $userId,
                'quantity' => 1,
            ]);
            return redirect()->route('cart.index')->with('success', 'Article ajouté au panier avec succès.');
        }
    }

    /**
     * Delete item from cart
     */
    public function delete($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour gérer votre panier.');
        }

        $cart = Cart::findOrFail($id);
        
        // Check if the cart item belongs to the authenticated user
        if ($cart->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Action non autorisée.');
        }

        $cart->delete();
        return redirect()->back()->with('success', 'Article retiré du panier.');
    }

    /**
     * Update cart item quantity
     */
    public function qty(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Non authentifié'], 401);
        }

        $cartId = $request->input('cart_id');
        $quantity = $request->input('quantity');
        
        $cart = Cart::find($cartId);
        
        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Article non trouvé'], 404);
        }

        // Check if the cart item belongs to the authenticated user
        if ($cart->user_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Action non autorisée'], 403);
        }

        if ($quantity > 0) {
            $cart->quantity = $quantity;
            $cart->save();
            return response()->json(['success' => true, 'quantity' => $cart->quantity]);
        }
        
        return response()->json(['success' => false, 'message' => 'Quantité invalide'], 400);
    }
}
