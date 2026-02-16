<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Concept;
use App\Models\ConceptDimension;
use App\Models\ConceptMeasure;
use App\Models\ConceptMetalOption;
use App\Models\ConceptWeight;
use App\Models\Room;
use App\Models\Metal;
use App\Models\MetalOption;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibraryConceptController extends Controller
{
    public function index(Request $request)
    {
        $query = Concept::where('source', 'library')
            ->with(['photos', 'category', 'rooms', 'metals']);

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

        return view('admin.library-concepts.index', compact('concepts'));
    }

    public function create()
    {
        return view('admin.library-concepts.create', [
            'categories' => Category::whereType('main')->get(),
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
            'reel' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:102400',
            'folderModel' => 'nullable|file|mimes:zip|max:51200',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
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
                return response()->json(['success' => false, 'message' => 'Veuillez ajouter au moins une mesure (taille, dimensions ou poids).'], 422);
            }
            return redirect()->back()->with('error', 'Veuillez ajouter au moins une mesure (taille, dimensions ou poids).')->withInput();
        }

        $concept = Concept::create([
            'name' => $request->name,
            'size' => $request->get('size', 'N/A'),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'user_id' => auth()->id(),
            'status' => 'inactive', // same as designer: active only after customize is saved
            'source' => 'library',
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
                'message' => 'Concept bibliothèque créé.',
                'concept_id' => $concept->id,
                'redirect_url' => route('admin.library-concepts.customize', $concept),
            ]);
        }

        return redirect()->route('admin.library-concepts.customize', $concept);
    }

    /**
     * Show customization page: choose metal option(s) per metal (same logic as designer).
     */
    public function customize(Concept $library_concept)
    {
        $this->ensureLibraryConcept($library_concept);
        $library_concept->load(['metals.metalOptions', 'conceptMetalOptions.metalOption']);
        $selectedOptionsByMetal = $library_concept->conceptMetalOptions->groupBy('metal_id')->map(fn ($rows) => $rows->pluck('metal_option_id'));
        return view('admin.library-concepts.customize', [
            'concept' => $library_concept,
            'selectedOptionsByMetal' => $selectedOptionsByMetal,
        ]);
    }

    /**
     * Save customization: selected metal_option_id(s) per metal_id (same logic as designer).
     */
    public function saveCustomize(Request $request, Concept $library_concept)
    {
        $this->ensureLibraryConcept($library_concept);
        $library_concept->load('metals');
        $metalIds = $library_concept->metals->pluck('id')->toArray();

        $request->validate([
            'options' => 'nullable|array',
            'options.*' => 'nullable|array',
            'options.*.*' => 'exists:metal_options,id',
        ]);

        ConceptMetalOption::where('concept_id', $library_concept->id)->delete();

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
                    'concept_id' => $library_concept->id,
                    'metal_id' => $metalId,
                    'metal_option_id' => $metalOptionId,
                ]);
            }
        }

        $library_concept->update(['status' => 'active']);

        return redirect()->route('admin.library-concepts.show', $library_concept)->with('success', 'Personnalisation enregistrée. Le concept est maintenant actif.');
    }

    public function uploadPhotos(Request $request, Concept $library_concept)
    {
        $this->ensureLibraryConcept($library_concept);
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
                'attachment_id' => $library_concept->id,
                'type' => 'concept',
            ]);
            $uploaded[] = $media;
        }
        
        // Check if request is AJAX
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Photos uploadées.', 'photos' => $uploaded]);
        }
        
        return redirect()->route('admin.library-concepts.show', $library_concept)->with('success', 'Photos ajoutées avec succès.');
    }

    public function uploadReel(Request $request, Concept $library_concept)
    {
        $this->ensureLibraryConcept($library_concept);
        $request->validate([
            'reel' => 'required|file|mimes:mp4,mov,ogg,qt,avi,wmv,flv,webm|max:204800',
        ]);
        if ($library_concept->reel && file_exists(public_path($library_concept->reel))) {
            unlink(public_path($library_concept->reel));
        }
        $file = $request->file('reel');
        $fileName = uniqid('conceptReel_') . '.' . $file->getClientOriginalExtension();
        $file->storeAs('uploads/reels', $fileName, 'public');
        $library_concept->update(['reel' => '/storage/uploads/reels/' . $fileName]);
        
        // Check if request is AJAX
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Reel uploadé.', 'reel_url' => $library_concept->reel]);
        }
        
        return redirect()->route('admin.library-concepts.show', $library_concept)->with('success', 'Reel ajouté avec succès.');
    }

    public function uploadModel(Request $request, Concept $library_concept)
    {
        $this->ensureLibraryConcept($library_concept);
        $request->validate([
            'folderModel' => 'required|file|mimes:zip|max:51200',
        ]);
        $existing = $library_concept->threedmodels;
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
            'attachment_id' => $library_concept->id,
            'type' => 'concept_threedmodel',
        ]);
        
        // Check if request is AJAX
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Modèle 3D uploadé.']);
        }
        
        return redirect()->route('admin.library-concepts.show', $library_concept)->with('success', 'Modèle 3D ajouté avec succès.');
    }

    public function deletePhoto(Concept $library_concept, Media $media)
    {
        $this->ensureLibraryConcept($library_concept);
        if ($media->attachment_id != $library_concept->id || $media->type !== 'concept') {
            abort(404, 'Photo not found.');
        }
        if (file_exists(public_path($media->url))) {
            unlink(public_path($media->url));
        }
        $media->delete();
        return redirect()->route('admin.library-concepts.show', $library_concept)->with('success', 'Photo supprimée.');
    }

    public function deleteReel(Concept $library_concept)
    {
        $this->ensureLibraryConcept($library_concept);
        if ($library_concept->reel && file_exists(public_path($library_concept->reel))) {
            unlink(public_path($library_concept->reel));
        }
        $library_concept->update(['reel' => null]);
        return redirect()->route('admin.library-concepts.show', $library_concept)->with('success', 'Reel supprimé.');
    }

    public function deleteModel(Concept $library_concept)
    {
        $this->ensureLibraryConcept($library_concept);
        $existing = $library_concept->threedmodels;
        if ($existing) {
            $this->deleteModelFiles($existing);
            $existing->delete();
        }
        return redirect()->route('admin.library-concepts.show', $library_concept)->with('success', 'Modèle 3D supprimé.');
    }

    public function show(Concept $library_concept)
    {
        $this->ensureLibraryConcept($library_concept);
        $library_concept->load([
            'category', 'rooms', 'metals', 'photos', 'threedmodels', 'measure.dimension', 'measure.weight',
            'conceptMetalOptions.metalOption.metal',
        ]);
        
        // Load data for edit modals
        $categories = Category::whereType('main')->with('sub_categories')->get();
        $rooms = Room::all();
        $metals = Metal::all();
        
        return view('admin.library-concepts.show', [
            'concept' => $library_concept,
            'categories' => $categories,
            'rooms' => $rooms,
            'metals' => $metals
        ]);
    }

    public function edit(Concept $library_concept)
    {
        $this->ensureLibraryConcept($library_concept);
        $library_concept->load(['photos', 'threedmodels', 'rooms', 'metals', 'measure.dimension', 'measure.weight']);
        return view('admin.library-concepts.edit', [
            'concept' => $library_concept,
            'categories' => Category::whereType('main')->get(),
            'rooms' => Room::all(),
            'metals' => Metal::all(),
        ]);
    }

    public function update(Request $request, Concept $library_concept)
    {
        $this->ensureLibraryConcept($library_concept);

        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'rooms' => 'required|array',
            'rooms.*' => 'exists:rooms,id',
            'metals' => 'required|array',
            'metals.*' => 'exists:metals,id',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
            'length' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'unit' => 'nullable|in:CM,FT,INCH',
            'measure_size' => 'nullable|in:SMALL,MEDIUM,LARGE',
            'weight_value' => 'nullable|numeric',
            'weight_unit' => 'nullable|in:KG,LB',
        ]);

        $library_concept->update([
            'name' => $request->name,
            'size' => $request->get('size', 'N/A'),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        $library_concept->rooms()->sync($request->rooms);
        $library_concept->metals()->sync($request->metals);

        $measure = $library_concept->measure;
        if (!$measure && ($request->filled('measure_size') || $request->filled('length') || $request->filled('weight_value'))) {
            $measure = ConceptMeasure::create([
                'concept_id' => $library_concept->id,
                'size' => $request->get('measure_size', 'MEDIUM'),
            ]);
        } elseif ($measure && $request->filled('measure_size')) {
            $measure->update(['size' => $request->measure_size]);
        }

        if ($measure && $request->filled('length') && $request->filled('height') && $request->filled('width')) {
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

        if ($measure && $request->filled('weight_value') && $request->filled('weight_unit')) {
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

        return redirect()->route('admin.library-concepts.index')->with('success', 'Concept bibliothèque mis à jour.');
    }

    public function destroy(Concept $library_concept)
    {
        $this->ensureLibraryConcept($library_concept);
        
        // Delete 3D model files if exists
        $existingModel = $library_concept->threedmodels;
        if ($existingModel) {
            $this->deleteModelFiles($existingModel);
        }
        
        $library_concept->delete();
        return redirect()->route('admin.library-concepts.index')->with('success', 'Concept bibliothèque supprimé.');
    }

    private function ensureLibraryConcept(Concept $concept): void
    {
        if ($concept->source !== 'library') {
            abort(404, 'Concept non trouvé ou non géré ici.');
        }
    }

    /**
     * Update basic information section
     */
    public function updateBasic(Request $request, Concept $library_concept)
    {
        $this->ensureLibraryConcept($library_concept);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'description' => 'required|string',
        ]);

        $library_concept->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Informations de base mises à jour avec succès.'
        ]);
    }

    /**
     * Update specifications section (rooms, metals, measurements)
     */
    public function updateSpecifications(Request $request, Concept $library_concept)
    {
        $this->ensureLibraryConcept($library_concept);

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
        $library_concept->rooms()->sync($request->rooms);
        $library_concept->metals()->sync($request->metals);

        // Update measurements
        $measure = $library_concept->measure;
        if (!$measure) {
            $measure = ConceptMeasure::create([
                'concept_id' => $library_concept->id,
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
