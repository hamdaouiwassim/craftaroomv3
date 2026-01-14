<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for Admin role (role 0)
| All routes require authentication and isAdmin middleware
|
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
    // Category Management
    Route::resource('categories', CategoryController::class);
    
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    
    // Team Member Management
    Route::resource('team-members', \App\Http\Controllers\Admin\TeamMemberController::class);
});

// Product Management - Available for Admin, Designer, and Constructor (not Client)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'canManageProducts'])->group(function () {
    Route::resource('products', ProductController::class);
    
    // Separate file upload endpoints for products
    Route::post('products/{product}/photos', [ProductController::class, 'uploadPhotos'])
        ->middleware(\App\Http\Middleware\IncreaseUploadLimits::class)
        ->name('products.upload-photos');
    
    Route::post('products/{product}/reel', [ProductController::class, 'uploadReel'])
        ->middleware(\App\Http\Middleware\IncreaseUploadLimits::class)
        ->name('products.upload-reel');
    
    Route::post('products/{product}/model', [ProductController::class, 'uploadModel'])
        ->middleware(\App\Http\Middleware\IncreaseUploadLimits::class)
        ->name('products.upload-model');
});

// Favorites route for admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
});
