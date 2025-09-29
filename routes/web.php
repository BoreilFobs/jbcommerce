<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategorieController;
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


Route::get('/team', [AboutController::class, 'team']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/contact', [MessageController::class, 'contact']);
Route::post('/message/create', [MessageController::class, 'store']);


Route::middleware('auth')->group(function () {

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
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

Route::get('/message/delete/{id}', [MessageController::class, 'delete']);

    Route::get('/offers', [OffersController::class, 'index'])->name('offer.index');
    Route::get('/offers/create-offer', [OffersController::class, 'createF'])->name('offer.create');
    Route::post('/offers/create', [OffersController::class, 'store'])->name('offer.store');
    Route::get('/offers/delete/{id}', [OffersController::class, 'delete'])->name('offer.delete');
    Route::get('/offers/update/{id}', [OffersController::class, 'updateF'])->name('offer.updateF');
    Route::put('/offers/{id}/update', [OffersController::class, 'update'])->name('offer.update');

    Route::get('/categories', [CategorieController::class, 'index'])->name('categories.index');
    Route::get('/categories/create-offer', [CategorieController::class, 'createF'])->name('categories.create');
    Route::post('/categories/create', [CategorieController::class, 'store'])->name('categories.store');
    Route::get('/categories/delete/{id}', [CategorieController::class, 'delete'])->name('categories.delete');
    Route::get('/categories/update/{id}', [CategorieController::class, 'updateF'])->name('categories.update');
    Route::put('/categories/{id}/update', [CategorieController::class, 'update'])->name('categories.updateF');


});

require __DIR__ . '/auth.php';
