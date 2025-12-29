<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Room;
use App\Models\Metal;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the landing page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get featured products (active products with photos)
        $products = Product::where('status', 'active')
            ->with(['photos', 'category', 'user'])
            ->latest()
            ->take(12)
            ->get();

        // Get team members from team_members table
        $teamMembers = \App\Models\TeamMember::where('is_active', true)
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('landing', [
            'products' => $products,
            'teamMembers' => $teamMembers
        ]);
    }

    /**
     * Display all products.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function products(Request $request)
    {
        $query = Product::where('status', 'active')
            ->with(['photos', 'category', 'user', 'rooms', 'metals']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by rooms
        if ($request->has('rooms') && is_array($request->rooms) && count($request->rooms) > 0) {
            $query->whereHas('rooms', function($q) use ($request) {
                $q->whereIn('rooms.id', $request->rooms);
            });
        }

        // Filter by metals
        if ($request->has('metals') && is_array($request->metals) && count($request->metals) > 0) {
            $query->whereHas('metals', function($q) use ($request) {
                $q->whereIn('metals.id', $request->metals);
            });
        }

        // Get filter data
        $categories = Category::whereHas('products', function($q) {
            $q->where('status', 'active');
        })->with('sub_categories')->get();
        
        $rooms = Room::whereHas('products', function($q) {
            $q->where('status', 'active');
        })->get();
        
        $metals = Metal::whereHas('products', function($q) {
            $q->where('status', 'active');
        })->get();

        // Get price range for filter
        $minPrice = Product::where('status', 'active')->min('price');
        $maxPrice = Product::where('status', 'active')->max('price');

        $products = $query->latest()->paginate(12)->withQueryString();

        return view('products', [
            'products' => $products,
            'categories' => $categories,
            'rooms' => $rooms,
            'metals' => $metals,
            'minPrice' => $minPrice ?? 0,
            'maxPrice' => $maxPrice ?? 10000,
        ]);
    }

    /**
     * Display product details.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        $product = Product::where('status', 'active')
            ->with([
                'photos', 
                'category', 
                'user', 
                'user.address',
                'user.avatar',
                'rooms', 
                'metals', 
                'measure.dimension', 
                'measure.weight', 
                'threedmodels',
                'reviews.user',
                'reviews.user.avatar'
            ])
            ->findOrFail($id);

        // Get other products from the same producer
        $producerProducts = Product::where('status', 'active')
            ->where('user_id', $product->user_id)
            ->where('id', '!=', $product->id)
            ->with(['photos', 'category'])
            ->latest()
            ->take(6)
            ->get();

        // Check if current user has favorited this product
        $isFavorite = false;
        if (auth()->check()) {
            $isFavorite = \App\Models\Favorite::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();
        }

        // Check if current user has reviewed this product
        $userReview = null;
        if (auth()->check()) {
            $userReview = \App\Models\Review::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->first();
        }

        // Calculate average rating
        $averageRating = $product->reviews->avg('rating');
        $totalReviews = $product->reviews->count();

        return view('product-details', [
            'product' => $product,
            'producerProducts' => $producerProducts,
            'isFavorite' => $isFavorite,
            'userReview' => $userReview,
            'averageRating' => $averageRating,
            'totalReviews' => $totalReviews,
        ]);
    }
}

