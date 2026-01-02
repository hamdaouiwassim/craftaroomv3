<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /**
     * Display the customer dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get cart count
        $cart = Session::get('cart', []);
        $cartCount = count($cart);
        
        // Get user statistics
        $stats = [
            'favorites_count' => $user->favorites()->count(),
            'reviews_count' => $user->reviews()->count(),
            'cart_count' => $cartCount,
            'recent_favorites' => $user->favorites()->with(['product.photos', 'product.category'])->latest()->take(6)->get(),
            'recent_reviews' => $user->reviews()->with(['product.photos'])->latest()->take(5)->get(),
        ];
        
        return view('customer.dashboard', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }
}
