<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Metal;
use App\Models\MetalOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MetalOptionController extends Controller
{
    /**
     * Store a new option for a metal.
     */
    public function store(Request $request, Metal $metal)
    {
        $data = $request->validate([
            'ref' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('metals/options', 'public');
            $data['image_url'] = Storage::url($imagePath);
        }

        $metal->metalOptions()->create($data);

        return redirect()->route('admin.metals.show', $metal)
            ->with('success', 'Sub metal option added.');
    }

    /**
     * Edit view for a sub option.
     */
    public function edit(Metal $metal, MetalOption $option)
    {
        abort_unless($option->metal_id === $metal->id, 404);
        return view('admin.metals.options.edit', compact('metal', 'option'));
    }

    /**
     * Update a sub option.
     */
    public function update(Request $request, Metal $metal, MetalOption $option)
    {
        abort_unless($option->metal_id === $metal->id, 404);

        $data = $request->validate([
            'ref' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($option->image_url) {
                $oldPath = str_replace('/storage/', '', $option->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            
            $imagePath = $request->file('image')->store('metals/options', 'public');
            $data['image_url'] = Storage::url($imagePath);
        }

        $option->update($data);

        return redirect()->route('admin.metals.show', $metal)
            ->with('success', 'Sub metal option updated.');
    }

    /**
     * Delete a sub option.
     */
    public function destroy(Metal $metal, MetalOption $option)
    {
        abort_unless($option->metal_id === $metal->id, 404);
        $option->delete();

        return redirect()->route('admin.metals.show', $metal)
            ->with('success', 'Sub metal option removed.');
    }
}
