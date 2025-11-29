<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CheackoutController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\WishesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SitemapController;
use App\Models\offers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', [WelcomeController::class, 'index'])->name("home");
Route::get('/shop', [WelcomeController::class, 'index'])->name("shop");
Route::get('/search', [WelcomeController::class, 'search'])->name('search');
Route::get('/product/details/{id}', [OffersController::class, 'show'])->name('product.details');

Route::get('/offers/new-arrivals', [WelcomeController::class, 'newArrivals'])->name('offers.newArrivals');

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');


//routes for admin - Protected by auth and admin middleware
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users management
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{id}', [UserController::class, 'show'])->name('admin.users.show');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/admin/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.resetPassword');
    
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

    // Admin Orders management
    Route::get('/admin/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/admin/orders/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::patch('/admin/orders/{id}/payment', [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('admin.orders.updatePayment');
    Route::patch('/admin/orders/{id}/tracking', [\App\Http\Controllers\Admin\OrderController::class, 'updateTracking'])->name('admin.orders.updateTracking');
    Route::delete('/admin/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('admin.orders.destroy');
    Route::get('/admin/orders/{id}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('admin.orders.invoice');
    Route::post('/admin/orders/bulk-update', [\App\Http\Controllers\Admin\OrderController::class, 'bulkUpdateStatus'])->name('admin.orders.bulkUpdate');
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
    Route::get('/wish-list', [WishesController::class, 'index'])->name('wishlist.index');
    Route::get('/wish-list/add/{id}', [WishesController::class, 'store'])->name('wishlist.add');
    Route::get('/wish-list/delete/{id}', [WishesController::class, 'delete'])->name('wishlist.delete');
    
    // Cart management
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
    Route::get('/cart/add/{id}', [CartController::class, 'store'])->name('cart.add');
    Route::post('/cart/qty', [CartController::class, 'qty'])->name('cart.qty');    // Checkout routes (NEW)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Old checkout route (keeping for backward compatibility)
    Route::get("/cheackout", [CheackoutController::class, 'index'])->name("cheackout");

    // Customer Orders management (NEW)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Product details (could be public, but keeping here for now)
});

// Public order tracking (NEW)
Route::get('/track-order', [OrderController::class, 'track'])->name('orders.track');
Route::post('/track-order', [OrderController::class, 'track']);

Route::fallback(function () {
    return response()->view('404', [], 404);
});
require __DIR__ . '/auth.php';
