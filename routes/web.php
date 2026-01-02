<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/products', [LandingController::class, 'products'])->name('products.index');
Route::get('/products/{id}', [LandingController::class, 'show'])->name('products.show');

// Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::put('/update/{product}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{product}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/dashboard', function () {
    if (auth()->user()->is_admin()) {
        $stats = [
            'total_products' => \App\Models\Product::count(),
            'active_products' => \App\Models\Product::where('status', 'active')->count(),
            'inactive_products' => \App\Models\Product::where('status', 'inactive')->count(),
            'total_categories' => \App\Models\Category::whereType('main')->count(),
            'total_users' => \App\Models\User::count(),
            'total_designers' => \App\Models\User::where('role', 1)->count(),
            'total_customers' => \App\Models\User::where('role', 2)->count(),
            'total_reviews' => \App\Models\Review::count(),
            'total_favorites' => \App\Models\Favorite::count(),
            'total_team_members' => \App\Models\TeamMember::where('is_active', true)->count(),
            'recent_products' => \App\Models\Product::with(['photos', 'category', 'user'])->latest()->take(5)->get(),
            'recent_users' => \App\Models\User::with('currency')->latest()->take(5)->get(),
        ];
        return view('admin.dashboard', compact('stats'));
    }
    // Redirect customers to their dashboard
    return redirect()->route('customer.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
    // Category Management
    Route::resource('categories', CategoryController::class);
    // Product Management
    Route::resource('products', ProductController::class);
    // Separate file upload endpoints for products
    Route::post('products/{product}/photos', [ProductController::class, 'uploadPhotos'])->middleware(\App\Http\Middleware\IncreaseUploadLimits::class)->name('products.upload-photos');
    Route::post('products/{product}/reel', [ProductController::class, 'uploadReel'])->middleware(\App\Http\Middleware\IncreaseUploadLimits::class)->name('products.upload-reel');
    Route::post('products/{product}/model', [ProductController::class, 'uploadModel'])->middleware(\App\Http\Middleware\IncreaseUploadLimits::class)->name('products.upload-model');
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    // Team Member Management
    Route::resource('team-members', \App\Http\Controllers\Admin\TeamMemberController::class);
});


Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer Routes
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [\App\Http\Controllers\Customer\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [\App\Http\Controllers\Customer\OrderController::class, 'show'])->name('orders.show');
        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    });
    
    // Keep original routes for backward compatibility
    Route::get('/my-orders', [\App\Http\Controllers\Customer\OrderController::class, 'index'])->name('my-orders');
    Route::get('/my-cart', [CartController::class, 'index'])->name('my-cart');
    Route::get('/my-profile', [ProfileController::class, 'edit'])->name('my-profile');

    // Review Routes
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Favorite Routes
    Route::post('/products/{product}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/products/{product}/favorite/check', [FavoriteController::class, 'check'])->name('favorites.check');
});

require __DIR__.'/auth.php';
