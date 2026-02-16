<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\FloorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FloorController extends Controller
{
    /**
     * Display a listing of floors with search functionality.
     */
    public function index(Request $request)
    {
        $query = Floor::query()->withCount('floorModels');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        $floors = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.floors.index', compact('floors'));
    }

    /**
     * Show the form for creating a new floor.
     */
    public function create()
    {
        return view('admin.floors.create');
    }

    /**
     * Store a newly created floor in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        // Handle icon upload
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('floors/icons', 'public');
            $data['icon'] = $iconPath;
        }

        Floor::create($data);

        return redirect()->route('admin.floors.index')
            ->with('success', 'Floor created successfully.');
    }

    /**
     * Display the specified floor and its models.
     */
    public function show(Floor $floor)
    {
        $floor->load('floorModels');
        return view('admin.floors.show', compact('floor'));
    }

    /**
     * Show the form for editing the specified floor.
     */
    public function edit(Floor $floor)
    {
        return view('admin.floors.edit', compact('floor'));
    }

    /**
     * Update the specified floor in storage.
     */
    public function update(Request $request, Floor $floor)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        // Handle icon upload
        if ($request->hasFile('icon')) {
            // Delete old icon if exists
            if ($floor->icon) {
                Storage::disk('public')->delete($floor->icon);
            }
            $iconPath = $request->file('icon')->store('floors/icons', 'public');
            $data['icon'] = $iconPath;
        }

        $floor->update($data);

        return redirect()->route('admin.floors.index')
            ->with('success', 'Floor updated successfully.');
    }

    /**
     * Remove the specified floor from storage.
     */
    public function destroy(Floor $floor)
    {
        // Delete icon if exists
        if ($floor->icon) {
            Storage::disk('public')->delete($floor->icon);
        }

        // Delete all floor models and their files
        foreach ($floor->floorModels as $model) {
            if ($model->path) {
                $fullPath = storage_path('app/public/' . $model->path);
                
                // Check if it's a directory (extracted ZIP)
                if (is_dir($fullPath)) {
                    Storage::disk('public')->deleteDirectory($model->path);
                } else {
                    Storage::disk('public')->delete($model->path);
                }
            }
            
            // Delete image if exists
            if ($model->image) {
                Storage::disk('public')->delete($model->image);
            }
            
            $model->delete();
        }

        $floor->delete();

        return redirect()->route('admin.floors.index')
            ->with('success', 'Floor deleted successfully.');
    }

    /**
     * Store a new floor model for a specific floor.
     */
    public function storeModel(Request $request, Floor $floor)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'model_file' => ['required', 'file', 'mimes:zip,glb,gltf', 'max:51200'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $modelPath = null;
        $modelUrl = null;
        $modelSize = null;
        $imagePath = null;

        // Handle model file upload
        if ($request->hasFile('model_file')) {
            $file = $request->file('model_file');
            $originalSize = $file->getSize();
            $extension = $file->getClientOriginalExtension();

            // If it's a ZIP file, extract it
            if ($extension === 'zip') {
                $extractPath = 'floors/models/' . uniqid();
                $zipPath = $file->storeAs('floors/temp', uniqid() . '.zip', 'public');
                $fullZipPath = storage_path('app/public/' . $zipPath);
                $fullExtractPath = storage_path('app/public/' . $extractPath);

                // Create extraction directory
                if (!file_exists($fullExtractPath)) {
                    mkdir($fullExtractPath, 0755, true);
                }

                // Extract ZIP
                $zip = new \ZipArchive;
                if ($zip->open($fullZipPath) === true) {
                    $zip->extractTo($fullExtractPath);
                    $zip->close();
                    
                    // Delete the temporary ZIP file
                    Storage::disk('public')->delete($zipPath);
                    
                    $modelPath = $extractPath;
                    $modelUrl = Storage::url($extractPath);
                } else {
                    return redirect()->back()->withErrors(['model_file' => 'Failed to extract ZIP file.']);
                }
            } else {
                // Store GLB/GLTF files directly
                $modelPath = $file->store('floors/models', 'public');
                $modelUrl = Storage::url($modelPath);
            }

            $modelSize = $this->formatBytes($originalSize);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('floors/images', 'public');
        }

        FloorModel::create([
            'floor_id' => $floor->id,
            'name' => $data['name'],
            'url' => $modelUrl,
            'path' => $modelPath,
            'size' => $modelSize,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.floors.show', $floor)
            ->with('success', 'Floor model uploaded successfully.');
    }

    /**
     * Delete a floor model.
     */
    public function destroyModel(Floor $floor, FloorModel $model)
    {
        // Delete model file/directory if exists
        if ($model->path) {
            $fullPath = storage_path('app/public/' . $model->path);
            
            // Check if it's a directory (extracted ZIP)
            if (is_dir($fullPath)) {
                // Delete directory recursively
                Storage::disk('public')->deleteDirectory($model->path);
            } else {
                // Delete single file
                Storage::disk('public')->delete($model->path);
            }
        }

        // Delete image if exists
        if ($model->image) {
            Storage::disk('public')->delete($model->image);
        }

        $model->delete();

        return redirect()->route('admin.floors.show', $floor)
            ->with('success', 'Floor model deleted successfully.');
    }

    /**
     * Format bytes to human readable size.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
