<?php

use App\Http\Controllers\ConceptController;
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
    
    // Concept Management (designer-only; no price/currency)
    Route::get('/concepts', [ConceptController::class, 'index'])->name('concepts.index');
    Route::get('/concepts/create', [ConceptController::class, 'create'])->name('concepts.create');
    Route::post('/concepts', [ConceptController::class, 'store'])->name('concepts.store');
    Route::get('/concepts/{concept}/edit', [ConceptController::class, 'edit'])->name('concepts.edit');
    Route::get('/concepts/{concept}/customize', [ConceptController::class, 'customize'])->name('concepts.customize');
    Route::post('/concepts/{concept}/customize', [ConceptController::class, 'saveCustomize'])->name('concepts.save-customize');
    Route::get('/concepts/{concept}', [ConceptController::class, 'show'])->name('concepts.show');
    Route::put('/concepts/{concept}', [ConceptController::class, 'update'])->name('concepts.update');
    Route::delete('/concepts/{concept}', [ConceptController::class, 'destroy'])->name('concepts.delete');
    Route::post('/concepts/{concept}/photos', [ConceptController::class, 'uploadPhotos'])
        ->middleware(\App\Http\Middleware\IncreaseUploadLimits::class)
        ->name('concepts.upload-photos');
    Route::delete('/concepts/{concept}/photos/{media}', [ConceptController::class, 'deletePhoto'])->name('concepts.delete-photo');
    Route::post('/concepts/{concept}/reel', [ConceptController::class, 'uploadReel'])
        ->middleware(\App\Http\Middleware\IncreaseUploadLimits::class)
        ->name('concepts.upload-reel');
    Route::delete('/concepts/{concept}/reel', [ConceptController::class, 'deleteReel'])->name('concepts.delete-reel');
    Route::post('/concepts/{concept}/model', [ConceptController::class, 'uploadModel'])
        ->middleware(\App\Http\Middleware\IncreaseUploadLimits::class)
        ->name('concepts.upload-model');
    Route::delete('/concepts/{concept}/model', [ConceptController::class, 'deleteModel'])->name('concepts.delete-model');
    
    // Favorites
    Route::get('/favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
});
