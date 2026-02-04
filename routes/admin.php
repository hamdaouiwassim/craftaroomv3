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
    // Library concepts (for constructors: "from library" option)
    Route::get('library-concepts', [\App\Http\Controllers\Admin\LibraryConceptController::class, 'index'])->name('library-concepts.index');
    Route::get('library-concepts/create', [\App\Http\Controllers\Admin\LibraryConceptController::class, 'create'])->name('library-concepts.create');
    Route::post('library-concepts', [\App\Http\Controllers\Admin\LibraryConceptController::class, 'store'])->name('library-concepts.store');
    Route::get('library-concepts/{library_concept}', [\App\Http\Controllers\Admin\LibraryConceptController::class, 'show'])->name('library-concepts.show');
    Route::get('library-concepts/{library_concept}/edit', [\App\Http\Controllers\Admin\LibraryConceptController::class, 'edit'])->name('library-concepts.edit');
    Route::put('library-concepts/{library_concept}', [\App\Http\Controllers\Admin\LibraryConceptController::class, 'update'])->name('library-concepts.update');
    Route::get('library-concepts/{library_concept}/customize', [\App\Http\Controllers\Admin\LibraryConceptController::class, 'customize'])->name('library-concepts.customize');
    Route::post('library-concepts/{library_concept}/customize', [\App\Http\Controllers\Admin\LibraryConceptController::class, 'saveCustomize'])->name('library-concepts.save-customize');
    Route::delete('library-concepts/{library_concept}', [\App\Http\Controllers\Admin\LibraryConceptController::class, 'destroy'])->name('library-concepts.destroy');
    Route::post('library-concepts/{library_concept}/photos', [\App\Http\Controllers\Admin\LibraryConceptController::class, 'uploadPhotos'])->name('library-concepts.upload-photos');
    Route::delete('library-concepts/{library_concept}/photos/{media}', [\App\Http\Controllers\Admin\LibraryConceptController::class, 'deletePhoto'])->name('library-concepts.delete-photo');
    Route::post('library-concepts/{library_concept}/reel', [\App\Http\Controllers\Admin\LibraryConceptController::class, 'uploadReel'])->middleware(\App\Http\Middleware\IncreaseUploadLimits::class)->name('library-concepts.upload-reel');
    Route::delete('library-concepts/{library_concept}/reel', [\App\Http\Controllers\Admin\LibraryConceptController::class, 'deleteReel'])->name('library-concepts.delete-reel');
    Route::post('library-concepts/{library_concept}/model', [\App\Http\Controllers\Admin\LibraryConceptController::class, 'uploadModel'])->middleware(\App\Http\Middleware\IncreaseUploadLimits::class)->name('library-concepts.upload-model');
    Route::delete('library-concepts/{library_concept}/model', [\App\Http\Controllers\Admin\LibraryConceptController::class, 'deleteModel'])->name('library-concepts.delete-model');

    // Select concepts (designer or library) for creating products
    Route::get('/concepts/select', [ProductController::class, 'selectConcepts'])->name('concepts.select');

    // Category Management
    Route::resource('categories', CategoryController::class);
    
    // Metal Management
    Route::resource('metals', \App\Http\Controllers\Admin\MetalController::class);
    
    // Room Management
    Route::resource('rooms', \App\Http\Controllers\RoomController::class);
    
    // Concept Management (Designer concepts)
    Route::resource('concepts', \App\Http\Controllers\ConceptController::class);
    Route::prefix('metals/{metal}')->name('metals.options.')->group(function () {
        Route::post('options', [\App\Http\Controllers\Admin\MetalOptionController::class, 'store'])->name('store');
        Route::get('options/{option}/edit', [\App\Http\Controllers\Admin\MetalOptionController::class, 'edit'])->name('edit');
        Route::put('options/{option}', [\App\Http\Controllers\Admin\MetalOptionController::class, 'update'])->name('update');
        Route::delete('options/{option}', [\App\Http\Controllers\Admin\MetalOptionController::class, 'destroy'])->name('destroy');
    });
    
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
