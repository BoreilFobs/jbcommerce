<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\WishesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [WelcomeController::class, 'index']);


//routes for admin
Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->name !== 'admin') {
        return redirect('/');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/offers', [OffersController::class, 'index']);
Route::get('/offers/create-offer', [OffersController::class, 'createF']);
Route::post('/offers/create', [OffersController::class, 'store']);
Route::get('/offers/delete/{id}', [OffersController::class, 'delete']);
Route::get('/offers/update/{id}', [OffersController::class, 'updateF']);
Route::put('/offers/{id}/update', [OffersController::class, 'update']);
Route::get('/team', [AboutController::class, 'team']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/contact', [MessageController::class, 'contact']);
Route::post('/message/create', [MessageController::class, 'store']);
Route::get('/message/delete/{id}', [MessageController::class, 'delete']);



Route::middleware('auth')->group(function () {

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/messages', [MessageController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Route::get('/products', [OffersController::class, 'index']);
    Route::get('/wish-list/{id}', [WishesController::class, 'index']);
    Route::get('/wish-list/{Oid}/create/{Uid}', [WishesController::class, 'store']);
    Route::get('/wish-list/create', [WishesController::class, 'store']);
    Route::get('/wish-list/delete/{id}', [WishesController::class, 'delete']);
    Route::get('/cart/{id}', [CartController::class, 'index']);
    Route::get('/cart/delete/{id}', [CartController::class, 'delete']);
    Route::get('/cart/{Oid}/create/{Uid}', [CartController::class, 'store']);
});

require __DIR__ . '/auth.php';
