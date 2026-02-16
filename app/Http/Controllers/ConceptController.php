<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\ConceptMeasure;
use App\Models\ConceptDimension;
use App\Models\ConceptWeight;
use App\Models\ConceptMetalOption;
use App\Models\Category;
use App\Models\Room;
use App\Models\Metal;
use App\Models\MetalOption;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConceptController extends Controller
{
    private function canAccessConcept(Concept $concept): bool
    {
        return $concept->user_id === auth()->id();
    }

    public function index(Request $request)
    {
        $query = Concept::where('user_id', auth()->id())
            ->with(['photos', 'category']);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $concepts = $query->latest()->paginate(15)->withQueryString();

        return view('designer.concepts.index', compact('concepts'));
    }

    public function create()
    {
        $categories = Category::whereType('main')->get();
        return view('designer.concepts.create', [
            'categories' => $categories,
            'rooms' => Room::all(),
            'metals' => Metal::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'rooms' => 'required|array',
            'rooms.*' => 'exists:rooms,id',
            'metals' => 'required|array',
            'metals.*' => 'exists:metals,id',
            'description' => 'required|string',
            'status' => 'nullable|in:active,inactive',
            // Files are uploaded separately via API after concept creation
            'reel' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:102400',
            'folderModel' => 'nullable|file|mimes:zip|max:51200',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            // Measurements
            'length' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'unit' => 'nullable|in:CM,FT,INCH',
            'measure_size' => 'nullable|in:SMALL,MEDIUM,LARGE',
            'weight_value' => 'nullable|numeric',
            'weight_unit' => 'nullable|in:KG,LB',
        ]);

        $hasMeasure = $request->filled('measure_size')
            || ($request->filled('length') && $request->filled('height') && $request->filled('width'))
            || ($request->filled('weight_value') && $request->filled('weight_unit'));

        if (!$hasMeasure) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez ajouter au moins une mesure (taille, dimensions ou poids).',
                    'errors' => ['measure' => ['Au moins une mesure est requise (taille, dimensions ou poids).']],
                ], 422);
            }
            return redirect()->back()->with('error', 'Veuillez ajouter au moins une mesure (taille, dimensions ou poids).')->withInput();
        }

        $concept = Concept::create([
            'name' => $request->name,
            'size' => $request->get('size', 'N/A'),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'user_id' => auth()->id(),
            'status' => 'inactive', // active only after customization is saved
            'source' => 'designer',
        ]);

        $concept->rooms()->sync($request->rooms);
        $concept->metals()->sync($request->metals);

        if ($request->filled('measure_size')) {
            $measure = ConceptMeasure::create([
                'concept_id' => $concept->id,
                'size' => $request->measure_size,
            ]);
            if ($request->filled('length') && $request->filled('height') && $request->filled('width')) {
                ConceptDimension::create([
                    'concept_measure_id' => $measure->id,
                    'length' => $request->length,
                    'height' => $request->height,
                    'width' => $request->width,
                    'unit' => $request->get('unit', 'CM'),
                ]);
            }
            if ($request->filled('weight_value') && $request->filled('weight_unit')) {
                ConceptWeight::create([
                    'concept_measure_id' => $measure->id,
                    'weight_value' => $request->weight_value,
                    'weight_unit' => $request->weight_unit,
                ]);
            }
        } elseif ($request->filled('length') && $request->filled('height') && $request->filled('width')) {
            $measure = ConceptMeasure::create([
                'concept_id' => $concept->id,
                'size' => $request->get('measure_size', 'MEDIUM'),
            ]);
            ConceptDimension::create([
                'concept_measure_id' => $measure->id,
                'length' => $request->length,
                'height' => $request->height,
                'width' => $request->width,
                'unit' => $request->get('unit', 'CM'),
            ]);
            if ($request->filled('weight_value') && $request->filled('weight_unit')) {
                ConceptWeight::create([
                    'concept_measure_id' => $measure->id,
                    'weight_value' => $request->weight_value,
                    'weight_unit' => $request->weight_unit,
                ]);
            }
        } elseif ($request->filled('weight_value') && $request->filled('weight_unit')) {
            $measure = ConceptMeasure::create([
                'concept_id' => $concept->id,
                'size' => $request->get('measure_size', 'MEDIUM'),
            ]);
            ConceptWeight::create([
                'concept_measure_id' => $measure->id,
                'weight_value' => $request->weight_value,
                'weight_unit' => $request->weight_unit,
            ]);
        }

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Concept created successfully.',
                'concept_id' => $concept->id,
            ]);
        }

        return redirect()->route('designer.concepts.customize', $concept);
    }

    /**
     * Show customization page: choose sub-metal (metal option) for each metal of the concept.
     */
    public function customize(Concept $concept)
    {
        if (!$this->canAccessConcept($concept)) {
            abort(403, 'Unauthorized. You can only customize your own concepts.');
        }
        $concept->load(['metals.metalOptions', 'conceptMetalOptions.metalOption']);
        // Group selected option IDs by metal_id for multiple choices per metal
        $selectedOptionsByMetal = $concept->conceptMetalOptions->groupBy('metal_id')->map(fn ($rows) => $rows->pluck('metal_option_id'));
        return view('designer.concepts.customize', compact('concept', 'selectedOptionsByMetal'));
    }

    /**
     * Save customization: selected metal_option_id(s) per metal_id (multiple choices per metal).
     */
    public function saveCustomize(Request $request, Concept $concept)
    {
        if (!$this->canAccessConcept($concept)) {
            abort(403, 'Unauthorized. You can only customize your own concepts.');
        }

        $concept->load('metals');
        $metalIds = $concept->metals->pluck('id')->toArray();

        $request->validate([
            'options' => 'nullable|array',
            'options.*' => 'nullable|array',
            'options.*.*' => 'exists:metal_options,id',
        ]);

        ConceptMetalOption::where('concept_id', $concept->id)->delete();

        $options = $request->input('options', []);
        foreach ($options as $metalId => $optionIds) {
            $metalId = (int) $metalId;
            if (!in_array($metalId, $metalIds, true)) {
                continue;
            }
            $optionIds = is_array($optionIds) ? array_unique(array_map('intval', $optionIds)) : [];
            foreach ($optionIds as $metalOptionId) {
                $option = MetalOption::where('id', $metalOptionId)->where('metal_id', $metalId)->first();
                if (!$option) {
                    continue;
                }
                ConceptMetalOption::create([
                    'concept_id' => $concept->id,
                    'metal_id' => $metalId,
                    'metal_option_id' => $metalOptionId,
                ]);
            }
        }

        $concept->update(['status' => 'active']);

        return redirect()->route('designer.concepts.show', $concept)->with('success', 'Personnalisation enregistrée. Le concept est maintenant actif.');
    }

    public function show(Concept $concept)
    {
        if (!$this->canAccessConcept($concept)) {
            abort(403, 'Unauthorized. You can only view your own concepts.');
        }
        $concept->load([
            'photos', 'threedmodels', 'rooms', 'metals', 'category', 'user',
            'measure.dimension', 'measure.weight',
            'conceptMetalOptions.metalOption.metal',
        ]);
        
        // Load data for edit modals
        $categories = Category::whereType('main')->with('sub_categories')->get();
        $rooms = Room::all();
        $metals = Metal::all();
        
        return view('designer.concepts.show', compact('concept', 'categories', 'rooms', 'metals'));
    }

    public function edit(Concept $concept)
    {
        if (!$this->canAccessConcept($concept)) {
            abort(403, 'Unauthorized. You can only edit your own concepts.');
        }
        $concept->load(['photos', 'threedmodels', 'rooms', 'metals', 'measure.dimension', 'measure.weight']);
        return view('designer.concepts.edit', [
            'concept' => $concept,
            'categories' => Category::whereType('main')->get(),
            'rooms' => Room::all(),
            'metals' => Metal::all(),
        ]);
    }

    public function update(Request $request, Concept $concept)
    {
        if (!$this->canAccessConcept($concept)) {
            abort(403, 'Unauthorized. You can only update your own concepts.');
        }

        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'rooms' => 'required|array',
            'rooms.*' => 'exists:rooms,id',
            'metals' => 'required|array',
            'metals.*' => 'exists:metals,id',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
            'size' => 'nullable|string',
            'length' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'unit' => 'nullable|in:CM,FT,INCH',
            'measure_size' => 'nullable|in:SMALL,MEDIUM,LARGE',
            'weight_value' => 'nullable|numeric',
            'weight_unit' => 'nullable|in:KG,LB',
        ]);

        $concept->update([
            'name' => $request->name,
            'size' => $request->get('size', 'N/A'),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        $concept->rooms()->sync($request->rooms);
        $concept->metals()->sync($request->metals);

        $measure = $concept->measure;
        if (!$measure) {
            $measure = ConceptMeasure::create(['concept_id' => $concept->id, 'size' => $request->get('measure_size', 'MEDIUM')]);
        } elseif ($request->filled('measure_size')) {
            $measure->update(['size' => $request->measure_size]);
        }

        if ($request->filled('length') && $request->filled('height') && $request->filled('width')) {
            $dim = $measure->dimension;
            if (!$dim) {
                $measure->dimension()->create([
                    'length' => $request->length,
                    'height' => $request->height,
                    'width' => $request->width,
                    'unit' => $request->get('unit', 'CM'),
                ]);
            } else {
                $dim->update([
                    'length' => $request->length,
                    'height' => $request->height,
                    'width' => $request->width,
                    'unit' => $request->get('unit', 'CM'),
                ]);
            }
        }

        if ($request->filled('weight_value') && $request->filled('weight_unit')) {
            $w = $measure->weight;
            if (!$w) {
                $measure->weight()->create([
                    'weight_value' => $request->weight_value,
                    'weight_unit' => $request->weight_unit,
                ]);
            } else {
                $w->update([
                    'weight_value' => $request->weight_value,
                    'weight_unit' => $request->weight_unit,
                ]);
            }
        }

        return redirect()->route('designer.concepts.index')->with('success', 'Concept updated successfully.');
    }

    public function destroy(Concept $concept)
    {
        if (!$this->canAccessConcept($concept)) {
            abort(403, 'Unauthorized. You can only delete your own concepts.');
        }
        
        // Delete 3D model files if exists
        $existingModel = $concept->threedmodels;
        if ($existingModel) {
            $this->deleteModelFiles($existingModel);
        }
        
        $concept->delete();
        return redirect()->route('designer.concepts.index')->with('success', 'Concept deleted successfully.');
    }

    public function uploadPhotos(Request $request, Concept $concept)
    {
        if (!$this->canAccessConcept($concept)) {
            return redirect()->route('designer.concepts.show', $concept)->with('error', 'Unauthorized.');
        }
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);
        $uploaded = [];
        foreach ($request->file('photos') ?? [] as $file) {
            $fileName = uniqid('conceptPhoto_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads/photos', $fileName, 'public');
            $media = Media::create([
                'name' => $fileName,
                'url' => '/storage/uploads/photos/' . $fileName,
                'attachment_id' => $concept->id,
                'type' => 'concept',
            ]);
            $uploaded[] = $media;
        }
        
        // Check if request is AJAX
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Photos uploaded.', 'photos' => $uploaded]);
        }
        
        return redirect()->route('designer.concepts.show', $concept)->with('success', 'Photos ajoutées avec succès.');
    }

    public function uploadReel(Request $request, Concept $concept)
    {
        if (!$this->canAccessConcept($concept)) {
            return redirect()->route('designer.concepts.show', $concept)->with('error', 'Unauthorized.');
        }
        $request->validate([
            'reel' => 'required|file|mimes:mp4,mov,ogg,qt,avi,wmv,flv,webm|max:204800',
        ]);
        if ($concept->reel && file_exists(public_path($concept->reel))) {
            unlink(public_path($concept->reel));
        }
        $file = $request->file('reel');
        $fileName = uniqid('conceptReel_') . '.' . $file->getClientOriginalExtension();
        $file->storeAs('uploads/reels', $fileName, 'public');
        $concept->update(['reel' => '/storage/uploads/reels/' . $fileName]);
        
        // Check if request is AJAX
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Reel uploaded.', 'reel_url' => $concept->reel]);
        }
        
        return redirect()->route('designer.concepts.show', $concept)->with('success', 'Reel ajouté avec succès.');
    }

    public function uploadModel(Request $request, Concept $concept)
    {
        if (!$this->canAccessConcept($concept)) {
            return redirect()->route('designer.concepts.show', $concept)->with('error', 'Unauthorized.');
        }
        $request->validate([
            'folderModel' => 'required|file|mimes:zip|max:51200',
        ]);
        $existing = $concept->threedmodels;
        if ($existing) {
            $this->deleteModelFiles($existing);
            $existing->delete();
        }
        
        $file = $request->file('folderModel');
        $extension = $file->getClientOriginalExtension();
        
        if ($extension === 'zip') {
            // Extract ZIP file
            $extractPath = 'uploads/models/' . uniqid('concept3d_');
            $zipPath = $file->storeAs('uploads/temp', uniqid() . '.zip', 'public');
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
                $modelUrl = '/storage/' . $extractPath;
            } else {
                return redirect()->back()->with('error', 'Failed to extract ZIP file.');
            }
        } else {
            // Store GLB/GLTF files directly
            $fileName = uniqid('concept3d_') . '.' . $extension;
            $modelPath = 'uploads/models/' . $fileName;
            $file->storeAs('uploads/models', $fileName, 'public');
            $modelUrl = '/storage/uploads/models/' . $fileName;
        }
        
        Media::create([
            'name' => basename($modelPath),
            'url' => $modelUrl,
            'attachment_id' => $concept->id,
            'type' => 'concept_threedmodel',
        ]);
        
        // Check if request is AJAX
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => '3D model uploaded.']);
        }
        
        return redirect()->route('designer.concepts.show', $concept)->with('success', 'Modèle 3D ajouté avec succès.');
    }

    public function deletePhoto(Concept $concept, Media $media)
    {
        if (!$this->canAccessConcept($concept)) {
            return redirect()->route('designer.concepts.show', $concept)->with('error', 'Unauthorized.');
        }
        if ($media->attachment_id != $concept->id || $media->type !== 'concept') {
            abort(404, 'Photo not found.');
        }
        if (file_exists(public_path($media->url))) {
            unlink(public_path($media->url));
        }
        $media->delete();
        return redirect()->route('designer.concepts.show', $concept)->with('success', 'Photo supprimée.');
    }

    public function deleteReel(Concept $concept)
    {
        if (!$this->canAccessConcept($concept)) {
            return redirect()->route('designer.concepts.show', $concept)->with('error', 'Unauthorized.');
        }
        if ($concept->reel && file_exists(public_path($concept->reel))) {
            unlink(public_path($concept->reel));
        }
        $concept->update(['reel' => null]);
        return redirect()->route('designer.concepts.show', $concept)->with('success', 'Reel supprimé.');
    }

    public function deleteModel(Concept $concept)
    {
        if (!$this->canAccessConcept($concept)) {
            return redirect()->route('designer.concepts.show', $concept)->with('error', 'Unauthorized.');
        }
        $existing = $concept->threedmodels;
        if ($existing) {
            $this->deleteModelFiles($existing);
            $existing->delete();
        }
        return redirect()->route('designer.concepts.show', $concept)->with('success', 'Modèle 3D supprimé.');
    }

    /**
     * Update basic information section
     */
    public function updateBasic(Request $request, Concept $concept)
    {
        if (!$this->canAccessConcept($concept)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'description' => 'required|string',
        ]);

        $concept->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Informations de base mises à jour avec succès.'
        ]);
    }

    /**
     * Update specifications section (rooms, metals, measurements)
     */
    public function updateSpecifications(Request $request, Concept $concept)
    {
        if (!$this->canAccessConcept($concept)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'rooms' => 'required|array',
            'rooms.*' => 'exists:rooms,id',
            'metals' => 'required|array',
            'metals.*' => 'exists:metals,id',
            'length' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'unit' => 'nullable|in:CM,FT,INCH',
            'measure_size' => 'nullable|in:SMALL,MEDIUM,LARGE',
            'weight_value' => 'nullable|numeric',
            'weight_unit' => 'nullable|in:KG,LB',
        ]);

        // Update rooms and metals
        $concept->rooms()->sync($request->rooms);
        $concept->metals()->sync($request->metals);

        // Update measurements
        $measure = $concept->measure;
        if (!$measure) {
            $measure = ConceptMeasure::create([
                'concept_id' => $concept->id,
                'size' => $request->get('measure_size', 'MEDIUM')
            ]);
        } elseif ($request->filled('measure_size')) {
            $measure->update(['size' => $request->measure_size]);
        }

        // Update dimensions
        if ($request->filled('length') && $request->filled('height') && $request->filled('width')) {
            $dim = $measure->dimension;
            if (!$dim) {
                $measure->dimension()->create([
                    'length' => $request->length,
                    'height' => $request->height,
                    'width' => $request->width,
                    'unit' => $request->get('unit', 'CM'),
                ]);
            } else {
                $dim->update([
                    'length' => $request->length,
                    'height' => $request->height,
                    'width' => $request->width,
                    'unit' => $request->get('unit', 'CM'),
                ]);
            }
        }

        // Update weight
        if ($request->filled('weight_value') && $request->filled('weight_unit')) {
            $w = $measure->weight;
            if (!$w) {
                $measure->weight()->create([
                    'weight_value' => $request->weight_value,
                    'weight_unit' => $request->weight_unit,
                ]);
            } else {
                $w->update([
                    'weight_value' => $request->weight_value,
                    'weight_unit' => $request->weight_unit,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Spécifications mises à jour avec succès.'
        ]);
    }

    /**
     * Delete model files (handles both directories and single files)
     */
    private function deleteModelFiles($media)
    {
        if (!$media) {
            return;
        }

        $filePath = public_path($media->url);
        
        // Check if it's a directory (extracted ZIP)
        if (is_dir($filePath)) {
            // Delete directory recursively
            $relativePath = str_replace('/storage/', '', $media->url);
            Storage::disk('public')->deleteDirectory($relativePath);
        } else {
            // Delete single file
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }
}
