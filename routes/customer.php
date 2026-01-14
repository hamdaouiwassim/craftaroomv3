<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
|
| Routes for Customer role (role 2)
| All routes require authentication
|
*/

Route::prefix('customer')->name('customer.')->middleware(['auth', 'isCustomer'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
    
    // Orders
    Route::get('/orders', [\App\Http\Controllers\Customer\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Customer\OrderController::class, 'show'])->name('orders.show');
    
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    
    // Favorites
    Route::get('/favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
});
