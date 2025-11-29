<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of all users (Admin)
     */
    public function index()
    {
        // Get all users except the current admin
        $users = User::where('id', '!=', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user details
     */
    public function show($id)
    {
        $user = User::with(['cartItems.offer', 'wishlistItems.offer'])->findOrFail($id);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy($id)
    {
        try {
            // Prevent admin from deleting themselves
            if ($id == Auth::id()) {
                return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            }

            // Prevent deleting the main admin
            $user = User::findOrFail($id);
            if ($user->name === 'admin') {
                return redirect()->back()->with('error', 'Le compte administrateur principal ne peut pas être supprimé.');
            }

            // Delete user's cart items
            $user->cartItems()->delete();

            // Delete user's wishlist items
            $user->wishlistItems()->delete();

            // Delete the user
            $user->delete();

            return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression de l\'utilisateur: ' . $e->getMessage());
        }
    }
    
    /**
     * Reset user password (Admin only)
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.required' => 'Le nouveau mot de passe est requis.',
            'new_password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'new_password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        try {
            $user = User::findOrFail($id);
            
            // Update password
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->back()->with('success', 'Mot de passe réinitialisé avec succès pour ' . $user->name . '.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la réinitialisation du mot de passe: ' . $e->getMessage());
        }
    }
}
