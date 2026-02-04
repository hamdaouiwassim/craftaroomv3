<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Concept;
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

        // Search functionality (trim to avoid spaces-only queries)
        $search = trim($request->input('search', ''));
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter by category (supports main category -> subcategories)
        $categoryId = $request->input('category');
        if ($categoryId) {
            $categoryIds = [$categoryId];
            $category = Category::with('sub_categories')->find($categoryId);
            if ($category && $category->sub_categories->count() > 0) {
                $categoryIds = array_merge($categoryIds, $category->sub_categories->pluck('id')->all());
            }
            $query->whereIn('category_id', $categoryIds);
        }

        // Filter by price range
        $minPriceFilter = $request->input('min_price', null);
        $maxPriceFilter = $request->input('max_price', null);
        if ($minPriceFilter !== null && $minPriceFilter !== '') {
            $query->where('price', '>=', $minPriceFilter);
        }
        if ($maxPriceFilter !== null && $maxPriceFilter !== '') {
            $query->where('price', '<=', $maxPriceFilter);
        }

        // Filter by rooms
        $rooms = $request->input('rooms', []);
        if (is_array($rooms) && count($rooms) > 0) {
            $query->whereHas('rooms', function($q) use ($rooms) {
                $q->whereIn('rooms.id', $rooms);
            });
        }

        // Filter by metals
        $metals = $request->input('metals', []);
        if (is_array($metals) && count($metals) > 0) {
            $query->whereHas('metals', function($q) use ($metals) {
                $q->whereIn('metals.id', $metals);
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

        $products = $query->latest()->paginate(10)->withQueryString();

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

    /**
     * Display all concepts (from designers and library).
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function concepts(Request $request)
    {
        $query = Concept::where('status', 'active')
            ->with(['photos', 'category', 'user', 'rooms', 'metals']);

        // Search functionality
        $search = trim($request->input('search', ''));
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter by source (designer or library)
        $source = $request->input('source');
        if ($source && in_array($source, ['designer', 'library'])) {
            $query->where('source', $source);
        }

        // Filter by category
        $categoryId = $request->input('category');
        if ($categoryId) {
            $categoryIds = [$categoryId];
            $category = Category::with('sub_categories')->find($categoryId);
            if ($category && $category->sub_categories->count() > 0) {
                $categoryIds = array_merge($categoryIds, $category->sub_categories->pluck('id')->all());
            }
            $query->whereIn('category_id', $categoryIds);
        }

        // Filter by rooms
        $rooms = $request->input('rooms', []);
        if (is_array($rooms) && count($rooms) > 0) {
            $query->whereHas('rooms', function($q) use ($rooms) {
                $q->whereIn('rooms.id', $rooms);
            });
        }

        // Filter by metals
        $metals = $request->input('metals', []);
        if (is_array($metals) && count($metals) > 0) {
            $query->whereHas('metals', function($q) use ($metals) {
                $q->whereIn('metals.id', $metals);
            });
        }

        // Get filter data
        $categories = Category::whereHas('concepts', function($q) {
            $q->where('status', 'active');
        })->with('sub_categories')->get();
        
        $roomsFilter = Room::whereHas('concepts', function($q) {
            $q->where('status', 'active');
        })->get();
        
        $metalsFilter = Metal::whereHas('concepts', function($q) {
            $q->where('status', 'active');
        })->get();

        $concepts = $query->latest()->paginate(12)->withQueryString();

        return view('concepts', [
            'concepts' => $concepts,
            'categories' => $categories,
            'rooms' => $roomsFilter,
            'metals' => $metalsFilter,
        ]);
    }

    /**
     * Display concept details.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showConcept($id)
    {
        $concept = Concept::where('status', 'active')
            ->with([
                'photos', 
                'category', 
                'user', 
                'rooms', 
                'metals', 
                'measure.dimension', 
                'measure.weight', 
                'threedmodels'
            ])
            ->findOrFail($id);

        // Get other concepts from the same source
        $relatedConcepts = Concept::where('status', 'active')
            ->where('source', $concept->source)
            ->where('id', '!=', $concept->id)
            ->with(['photos', 'category'])
            ->latest()
            ->take(6)
            ->get();

        return view('concept-details', [
            'concept' => $concept,
            'relatedConcepts' => $relatedConcepts,
        ]);
    }
}

