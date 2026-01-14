<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Designer Routes
|--------------------------------------------------------------------------
|
| Routes for Designer role (role 1)
| All routes require authentication and role:1 middleware
|
*/

Route::prefix('designer')->name('designer.')->middleware(['auth', 'isDesigner'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\DesignerController::class, 'dashboard'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\DesignerController::class, 'profile'])->name('profile.edit');
    
    // Product Management Routes - Specific routes must come before parameterized routes
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
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
