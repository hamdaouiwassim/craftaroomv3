<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Metal;
use App\Models\MetalOption;
use Illuminate\Http\Request;

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
            'image_url' => ['nullable', 'url', 'max:2048'],
        ]);

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
            'image_url' => ['nullable', 'url', 'max:2048'],
        ]);

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
