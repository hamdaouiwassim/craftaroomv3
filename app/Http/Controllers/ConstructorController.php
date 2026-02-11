<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\Product;
use Illuminate\Http\Request;

class ConstructorController extends Controller
{
    /**
     * List concepts by source (designer or library) so constructor can pick one to create a product.
     */
    public function selectConcepts(Request $request)
    {
        $source = $request->get('source', 'designer');
        if (!in_array($source, ['designer', 'library'], true)) {
            $source = 'designer';
        }

        $query = Concept::where('source', $source)
            ->where('status', 'active')
            ->with(['category', 'photos', 'rooms', 'metals', 'measure.dimension', 'measure.weight', 'threedmodels']);

        // Search functionality
        $search = trim($request->input('search', ''));
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter by category
        $categoryId = $request->input('category');
        if ($categoryId) {
            $categoryIds = [$categoryId];
            $category = \App\Models\Category::with('sub_categories')->find($categoryId);
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

        $concepts = $query->latest()->paginate(12)->withQueryString();

        // Get filter data
        $categories = \App\Models\Category::whereHas('concepts', function($q) use ($source) {
            $q->where('source', $source)->where('status', 'active');
        })->with('sub_categories')->get();
        
        $roomsFilter = \App\Models\Room::whereHas('concepts', function($q) use ($source) {
            $q->where('source', $source)->where('status', 'active');
        })->get();
        
        $metalsFilter = \App\Models\Metal::whereHas('concepts', function($q) use ($source) {
            $q->where('source', $source)->where('status', 'active');
        })->get();

        return view('constructor.concepts.select', [
            'concepts' => $concepts,
            'source' => $source,
            'categories' => $categories,
            'rooms' => $roomsFilter,
            'metals' => $metalsFilter,
        ]);
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        return view('constructor.index');
    }

    /**
     * Show the constructor's products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function products(Request $request)
    {
        $query = Product::where('user_id', auth()->id());

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } else {
            // Default to active products
            $query->where('status', 'active');
        }

        $products = $query->with(['photos', 'category', 'user'])->latest()->paginate(15);
        
        return view('constructor.products.index', ["products" => $products]);
    }

    /**
     * Display the constructor's profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile(Request $request)
    {
        return view('constructor.profile', [
            'user' => $request->user(),
        ]);
    }
}
