<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is admin
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Check if the authenticated user is admin
        // You can change this logic based on your needs
        // Option 1: Check by name (current implementation)
        if (Auth::user()->role !== 'admin') {
            // Redirect regular users to homepage
            return redirect('/')->with('error', 'Accès non autorisé. Cette page est réservée aux administrateurs.');
        }

        // Option 2: If you add a 'role' or 'is_admin' column to users table, use this instead:
        // if (!Auth::user()->is_admin) {
        //     return redirect('/')->with('error', 'Accès non autorisé. Cette page est réservée aux administrateurs.');
        // }

        return $next($request);
    }
}
