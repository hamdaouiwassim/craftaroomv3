<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| Routes accessible to all users (including guests)
|
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/products', [LandingController::class, 'products'])->name('products.index');
Route::get('/products/{id}', [LandingController::class, 'show'])->name('products.show');
Route::get('/concepts', [LandingController::class, 'concepts'])->name('concepts.index');
Route::get('/concepts/{id}', [LandingController::class, 'showConcept'])->name('concepts.show');
Route::get('/producer/{id}', [LandingController::class, 'showProducer'])->name('producer.show');

// Construction Requests (authenticated users)
Route::middleware('auth')->group(function () {
    Route::post('/concepts/{concept}/request-construction', [\App\Http\Controllers\ConstructionRequestController::class, 'store'])->name('construction-requests.store');
});

// Cart Routes (public)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::put('/update/{product}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{product}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

// Static Pages
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

/*
|--------------------------------------------------------------------------
| Dashboard Route (Role-based redirect)
|--------------------------------------------------------------------------
|
| Redirects users to their role-specific dashboard
|
*/

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    // Redirect based on user role
    if ($user->is_admin()) {
        // Admin (role 0)
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
    } elseif ($user->role === 1) {
        // Designer (role 1)
        return redirect()->route('designer.dashboard');
    } elseif ($user->role === 3) {
        // Constructor (role 3)
        return redirect()->route('constructor.dashboard');
    } else {
        // Customer (role 2) or any other role
        return redirect()->route('customer.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Role-based Route Files
|--------------------------------------------------------------------------
|
| Routes are separated by role for better organization
|
*/

// Admin Routes
require __DIR__.'/admin.php';

// Designer Routes
require __DIR__.'/designer.php';

// Constructor Routes
require __DIR__.'/constructor.php';

// Customer Routes
require __DIR__.'/customer.php';

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
|
| Routes available to all authenticated users
|
*/

Route::middleware('auth')->group(function () {
    // Profile Management (available to all authenticated users)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Backward Compatibility Routes
    Route::get('/my-orders', [\App\Http\Controllers\Customer\OrderController::class, 'index'])->name('my-orders');
    Route::get('/my-cart', [CartController::class, 'index'])->name('my-cart');
    Route::get('/my-profile', [ProfileController::class, 'edit'])->name('my-profile');

    // Review Routes (available to all authenticated users)
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Favorite Routes (available to all authenticated users)
    Route::post('/products/{product}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/products/{product}/favorite/check', [FavoriteController::class, 'check'])->name('favorites.check');
});

// Authentication Routes
require __DIR__.'/auth.php';
