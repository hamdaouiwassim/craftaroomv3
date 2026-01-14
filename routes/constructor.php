<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Constructor Routes
|--------------------------------------------------------------------------
|
| Routes for Constructor role (role 3)
| All routes require authentication and role:3 middleware
|
*/

Route::prefix('constructor')->name('constructor.')->middleware(['auth', 'isConstructor'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\ConstructorController::class, 'dashboard'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\ConstructorController::class, 'profile'])->name('profile.edit');
    
    // Product Management Routes - Specific routes must come before parameterized routes
    Route::get('/products', [\App\Http\Controllers\ConstructorController::class, 'products'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');
    
    // File upload routes for products
    Route::post('/products/{product}/photos', [ProductController::class, 'uploadPhotos'])
        ->middleware(\App\Http\Middleware\IncreaseUploadLimits::class)
        ->name('products.upload-photos');
    
    Route::post('/products/{product}/reel', [ProductController::class, 'uploadReel'])
        ->middleware(\App\Http\Middleware\IncreaseUploadLimits::class)
        ->name('products.upload-reel');
    
    Route::post('/products/{product}/model', [ProductController::class, 'uploadModel'])
        ->middleware(\App\Http\Middleware\IncreaseUploadLimits::class)
        ->name('products.upload-model');
    
    // Favorites
    Route::get('/favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
});
