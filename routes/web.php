<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/about', function () {
    return view('about');
});
Route::get('/wishlist', function () {
    return view('wishlist');
});
Route::get('/contact', function () {
    return view('contact');
});
Route::get('/cart', function () {
    return view('cart');
});
Route::get('/products', function () {
    return view('products');
});

Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->name !== 'admin') {
        return redirect('/');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
