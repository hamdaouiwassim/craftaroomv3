<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Concept;

class DesignerController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        return view('designer.index');
    }

    /**
     * Display the designer's profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile(Request $request)
    {
        return view('designer.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display products based on this designer's concepts
     * Only shows products created by constructors or admins
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function products(Request $request)
    {
        // Get all concept IDs created by this designer
        $conceptIds = Concept::where('user_id', auth()->id())
            ->where('source', 'designer')
            ->pluck('id');

        // Get all products that were created from these concepts
        // Only include products created by constructors (role = 3) or admins (role = 1)
        // Exclude products created by the designer themselves
        $query = Product::whereIn('concept_id', $conceptIds)
            ->where('user_id', '!=', auth()->id())
            ->whereHas('user', function($q) {
                $q->whereIn('role', [1, 3]); // 1 = admin, 3 = constructor
            });

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
        }

        $products = $query->with(['photos', 'category', 'user', 'concept'])
            ->latest()
            ->paginate(15);

        return view('designer.products.index', [
            'products' => $products,
        ]);
    }
}
