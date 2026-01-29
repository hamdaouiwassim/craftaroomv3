<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Metal;
use Illuminate\Http\Request;

class MetalController extends Controller
{
    /**
     * List metals with search and option counts.
     */
    public function index(Request $request)
    {
        $query = Metal::query()->withCount('metalOptions');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('ref', 'like', '%' . $search . '%');
            });
        }

        $metals = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.metals.index', compact('metals'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.metals.create');
    }

    /**
     * Store metal.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'ref' => ['nullable', 'string', 'max:100', 'unique:metals,ref'],
            'name' => ['required', 'string', 'max:255'],
            'image_url' => ['nullable', 'url', 'max:2048'],
        ]);

        Metal::create($data);

        return redirect()->route('admin.metals.index')
            ->with('success', 'Metal created successfully.');
    }

    /**
     * Show metal and its options.
     */
    public function show(Metal $metal)
    {
        $metal->load('metalOptions');
        return view('admin.metals.show', compact('metal'));
    }

    /**
     * Edit form.
     */
    public function edit(Metal $metal)
    {
        return view('admin.metals.edit', compact('metal'));
    }

    /**
     * Update metal.
     */
    public function update(Request $request, Metal $metal)
    {
        $data = $request->validate([
            'ref' => ['nullable', 'string', 'max:100', 'unique:metals,ref,' . $metal->id],
            'name' => ['required', 'string', 'max:255'],
            'image_url' => ['nullable', 'url', 'max:2048'],
        ]);

        $metal->update($data);

        return redirect()->route('admin.metals.index')
            ->with('success', 'Metal updated successfully.');
    }

    /**
     * Delete metal and detach relations.
     */
    public function destroy(Metal $metal)
    {
        // Clean pivots and options
        $metal->products()->detach();
        $metal->metalOptions()->delete();
        $metal->delete();

        return redirect()->route('admin.metals.index')
            ->with('success', 'Metal deleted successfully.');
    }
}
