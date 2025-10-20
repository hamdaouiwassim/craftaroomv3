<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CountryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\MetalController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\MaterialController;

Route::post("register",[AuthController::class,'register']);
Route::post("login",[AuthController::class,'login']);
Route::middleware(['auth:api'])->group(function () {
    Route::get('/myproducts', [UserController::class, 'products'])->name('user.products');
    Route::post('/user/update', [UserController::class, 'updateUser'])->name('user.update');
    Route::post('/user/password/update', [UserController::class, 'updatePassword'])->name('user.password.update');
    Route::post('/user/address/update',[UserController::class, 'updateAddress'])->name('user.address.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');

});

/** Guest endpoints */
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/countries', [CountryController::class, 'countries'])->name('countries');
Route::get('/currencies', [CountryController::class, 'getAllCurrencies'])->name('currencies');
Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
Route::get('/metals', [MetalController::class, 'index'])->name('metals.index');
Route::get('/metals/withProducts', [MetalController::class, 'withProducts'])->name('metals.withProducts');
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/withProducts', [RoomController::class, 'withProducts'])->name('rooms.withProducts');
Route::get("/products",[ProductController::class,'index'])->name('products.index');
Route::any('{path}', function() {
    return response()->json([
        'message' => 'Route not found'
    ], 404);
})->where('path', '.*');




    Route::any('{path}', function() {
        return response()->json([
            'message' => 'Route not found'
        ], 404);
    })->where('path', '.*');


