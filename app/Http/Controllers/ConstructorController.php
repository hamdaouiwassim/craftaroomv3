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

        $concepts = Concept::where('source', $source)
            ->where('status', 'active')
            ->with(['category', 'photos', 'rooms', 'metals'])
            ->latest()
            ->paginate(12);

        return view('constructor.concepts.select', [
            'concepts' => $concepts,
            'source' => $source,
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
