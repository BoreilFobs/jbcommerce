<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CheackoutController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\WishesController;
use App\Models\offers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', [WelcomeController::class, 'index'])->name("home");
Route::get('/shop', [WelcomeController::class, 'index'])->name("shop");
Route::get('/search', [WelcomeController::class, 'search'])->name('search');
Route::get('/offers/new-arrivals', [WelcomeController::class, 'newArrivals'])->name('offers.newArrivals');


//routes for admin - Protected by auth and admin middleware
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Users management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    
    // Messages management
    Route::get('/messages', [MessageController::class, 'index']);
    Route::get('/message/delete/{id}', [MessageController::class, 'delete']);

    // Offers/Products management
    Route::get('/offers', [OffersController::class, 'index'])->name('offer.index');
    Route::get('/offers/create-offer', [OffersController::class, 'createF'])->name('offer.create');
    Route::post('/offers/create', [OffersController::class, 'store'])->name('offer.store');
    Route::get('/offers/delete/{id}', [OffersController::class, 'delete'])->name('offer.delete');
    Route::get('/offers/update/{id}', [OffersController::class, 'updateF'])->name('offer.updateF');
    Route::put('/offers/{id}/update', [OffersController::class, 'update'])->name('offer.update');

    // Categories management
    Route::get('/categories', [CategorieController::class, 'index'])->name('categories.index');
    Route::get('/categories/create-offer', [CategorieController::class, 'createF'])->name('categories.create');
    Route::post('/categories/create', [CategorieController::class, 'store'])->name('categories.store');
    Route::get('/categories/delete/{id}', [CategorieController::class, 'delete'])->name('categories.delete');
    Route::get('/categories/update/{id}', [CategorieController::class, 'updateF'])->name('categories.update');
    Route::put('/categories/{id}/update', [CategorieController::class, 'update'])->name('categories.updateF');
});



Route::get('/team', [AboutController::class, 'team']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/contact', [MessageController::class, 'contact'])->name("contact");
Route::post('/message/create', [MessageController::class, 'store']);

// Customer routes - Protected by auth middleware
Route::middleware('auth')->group(function () {
    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Wishlist management
    Route::get('/wish-list/{id}', [WishesController::class, 'index']);
    Route::get('/wish-list', [WishesController::class, 'redirect']);
    Route::get('/wish-list/{Oid}/create/{Uid}', [WishesController::class, 'store']);
    Route::get('/wish-list/delete/{id}', [WishesController::class, 'delete']);
    
    // Cart management
    Route::get('/cart/{id}', [CartController::class, 'index']);
    Route::get('/cart', [CartController::class, 'redirect']);
    Route::get('/cart/delete/{id}', [CartController::class, 'delete']);
    Route::get('/cart/{Oid}/create/{Uid}', [CartController::class, 'store']);
    Route::post('/cart/qty', [CartController::class, 'qty'])->name('cart.qty');

    // Checkout
    Route::get("/cheackout", [CheackoutController::class, 'index'])->name("cheackout");

    // Product details (could be public, but keeping here for now)
    Route::get('/product/details/{id}', [OffersController::class, 'show'])->name('product.details');
});

Route::fallback(function () {
    return response()->view('404', [], 404);
});
require __DIR__ . '/auth.php';
