<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\Currency;
use App\Models\Room;
use App\Models\Media;
use App\Models\Metal;
use App\Models\MetalOption;
use App\Models\Weight;
use App\Models\Measure;
use App\Models\Product;
use App\Models\ProductMetalOption;
use App\Models\ProductCustomMetalOption;
use App\Models\Category;
use App\Models\Dimension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Check if user can access/modify this product
     * Admins can access all products
     * Designers and Constructors can only access their own products
     */
    private function canAccessProduct(Product $product)
    {
        $user = auth()->user();
        
        // Admins can access all products
        if ($user->is_admin()) {
            return true;
        }
        
        // Designers and Constructors can only access their own products
        return $product->user_id === $user->id;
    }

    /**
     * Get the view prefix based on user role
     * Returns: 'admin', 'designer', 'constructor', or 'customer'
     */
    private function getViewPrefix()
    {
        $user = auth()->user();
        
        if ($user->is_admin()) {
            return 'admin';
        } elseif ($user->role === 1) {
            return 'designer';
        } elseif ($user->role === 3) {
            return 'constructor';
        } else {
            return 'customer';
        }
    }

    /**
     * Get the route prefix for redirects based on user role
     * Returns: 'admin', 'designer', 'constructor', or 'customer'
     */
    private function getRoutePrefix()
    {
        $user = auth()->user();
        
        if ($user->is_admin()) {
            return 'admin';
        } elseif ($user->role === 1) {
            return 'designer';
        } elseif ($user->role === 3) {
            return 'constructor';
        } else {
            return 'customer';
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter by user for non-admin users (Designers and Constructors)
        if (!auth()->user()->is_admin()) {
            $query->where('user_id', auth()->id());
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } else {
            // Default to active products
            $query->where('status', 'active');
        }

        $products = $query->with(['photos', 'category', 'user'])->latest()->paginate(15);
        
        // Return JSON for AJAX requests (real-time search)
        if ($request->ajax() || $request->has('_ajax')) {
            return response()->json([
                'results' => $products->items(),
                'count' => $products->count(),
                'total' => $products->total()
            ]);
        }
        
        $viewPrefix = $this->getViewPrefix();
        $routePrefix = $this->getRoutePrefix();
        return view("{$viewPrefix}.products.index", [
            "products" => $products,
            "routePrefix" => $routePrefix
        ]);
    }

    /**
     * List concepts by source (designer or library) so admin/constructor can pick one to create a product.
     */
    public function selectConcepts(Request $request)
    {
        $source = $request->get('source', 'designer');
        if (!in_array($source, ['designer', 'library'], true)) {
            $source = 'designer';
        }

        $concepts = Concept::where('source', $source)
            ->where('status', 'active')
            ->with(['category', 'photos', 'rooms', 'metals', 'user'])
            ->latest()
            ->paginate(12);

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.concepts.select", [
            'concepts' => $concepts,
            'source' => $source,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * For admin/constructor: optional concept_id pre-fills the form from that concept.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categories = Category::whereType("main")->get();
        $viewPrefix = $this->getViewPrefix();
        $routePrefix = $this->getRoutePrefix();

        $concept = null;
        if (in_array($routePrefix, ['admin', 'constructor']) && $request->filled('concept_id')) {
            $concept = Concept::with([
                'category', 'rooms', 'metals',
                'measure.dimension', 'measure.weight',
                'photos', 'threedmodels',
            ])->find($request->get('concept_id'));
        }

        return view("{$viewPrefix}.products.create", [
            'categories' => $categories,
            'rooms' => Room::all(),
            'metals' => Metal::with('metalOptions')->get(),
            'currencies' => Currency::orderBy('name', 'asc')->get(),
            'routePrefix' => $routePrefix,
            'concept' => $concept,
             ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //dd($request);

        try{
            $validated = $request->validate([
                'name' => 'required|max:255',
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'style_type' => 'nullable|in:standard,artisant',
                'rooms' => 'required|array',
                'rooms.*' => 'exists:rooms,id',
                'description' => 'required|string',
                'folderModel' => 'nullable|file|mimes:zip|max:51200',
                'photos' => 'nullable|array',
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
                'metals' => 'required|array',
                'metals.*' => 'exists:metals,id',
                'currency' => 'required|string',
                'status' => 'nullable|in:active,inactive',
                'reel' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:102400',
                'concept_id' => 'nullable|exists:concepts,id',
                'is_resizable' => 'nullable|boolean',
                'concept_photos' => 'nullable|array',
                'concept_photos.*' => 'nullable|string',
                'concept_3d_model' => 'nullable|string',
                'concept_reel' => 'nullable|string',
            ]);
            
            // Validate that products have either uploaded 3D model OR concept 3D model.
            // Important: the JS flow creates the product via JSON first, then uploads the ZIP in a second request
            // (/{prefix}/products/{id}/model). In that case, folderModel is expected to be missing here.
            $isJsonFlow = $request->expectsJson() || $request->wantsJson();
            if (!$isJsonFlow && !$request->hasFile('folderModel') && !$request->filled('concept_3d_model')) {
                return redirect()->back()->withErrors(['folderModel' => 'Le modèle 3D est obligatoire.'])->withInput();
            }

            $m = false;
            $measure = new Measure();
            $dimension = null;
            $weight = null;
            
            // Handle dimensions
            if (  $request->has("length") && !empty($request->length) ) {
                $validated = $request->validate([
                    'length' => 'required|numeric',
                    'height' => 'required|numeric',
                    'width' => 'required|numeric',
                    'unit' => 'required|in:CM,FT,INCH',

                ]);
                    $dimension = new Dimension();
                    $dimension->length = $request->length;
                    $dimension->height = $request->height;
                    $dimension->width = $request->width;
                    $dimension->unit = $request->unit;
                    $m = true;
            }
            
            // Handle measure size (enum: SMALL, MEDIUM, LARGE)
            if (  $request->has("measure_size") && !empty($request->measure_size) ) {
                $validated = $request->validate([
                    'measure_size' => 'required|in:SMALL,MEDIUM,LARGE',
                ]);
                $measure->size = $request->measure_size;
                $m = true;
            }
            
            // Handle weight
            if (  $request->has("weight_value") && !empty($request->weight_value) ) {
                $validated = $request->validate([
                    'weight_value' => 'required|numeric',
                    'weight_unit' => 'required|in:KG,LB',

                ]);
                $weight = new Weight();
                $weight->weight_value = $request->weight_value;
                $weight->weight_unit = $request->weight_unit;
                $m = true;
            }

            if (!$m){
                if ($request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please add product measure...'
                    ], 422);
                }
                return redirect()->back()->with('error','Please add product measure...');
            }

            $inputs = $request->all();
            $inputs['user_id'] = auth()->user()->id;
            
            // Don't include file uploads in initial creation
            unset($inputs['reel'], $inputs['folderModel'], $inputs['photos']);
            $conceptId = $request->filled('concept_id') ? (int) $request->concept_id : null;
            
            // Keep concept_id in inputs if it exists
            if ($conceptId) {
                $inputs['concept_id'] = $conceptId;
            } else {
                unset($inputs['concept_id']);
            }

            $inputs['is_resizable'] = $request->boolean('is_resizable');
            
            // Set default size if not provided (since we removed it from the form)
            if (!isset($inputs['size']) || empty($inputs['size'])) {
                $inputs['size'] = 'N/A';
            }
            
        $product = Product::create($inputs);

        foreach($request->rooms as $room){
            $product->rooms()->attach($room);
        }
        foreach($request->metals as $metal){
            $product->metals()->attach($metal);
            }
            if (empty($measure->size)) {
                $measure->size = 'MEDIUM';
            }
            $measure->product_id = $product->id;
            $measure->save();

            if ($dimension) {
            $dimension->measure_id =  $measure->id;
                $dimension->save();
            }
            if ($weight) {
                $weight->measure_id =  $measure->id;
                $weight->save();}

            // Constructor creating from concept: if no new files will be uploaded (handled by JS), copy concept media to product so dropzone-empty = use concept media
            if ($conceptId && in_array($this->getRoutePrefix(), ['constructor', 'admin'])) {
                $concept = Concept::with([
                    'photos',
                    'threedmodels',
                    'conceptMetalOptions',
                    'conceptCustomMetalOptions',
                ])->find($conceptId);
                if ($concept) {
                    // Only copy concept photos that weren't deleted (i.e., present in concept_photos input)
                    if ($request->has('concept_photos')) {
                        $conceptPhotosToKeep = $request->input('concept_photos', []);
                        foreach ($concept->photos as $photo) {
                            // Only create media if this photo ID is in the concept_photos array
                            if (isset($conceptPhotosToKeep[$photo->id])) {
                                Media::create([
                                    'name' => $photo->name,
                                    'url' => $photo->url,
                                    'type' => 'product',
                                    'attachment_id' => $product->id,
                                ]);
                            }
                        }
                    }
                    
                    // Only use concept 3D model if user didn't upload a new one
                    if ($concept->threedmodels && $request->has('concept_3d_model') && !$request->hasFile('folderModel')) {
                        Media::create([
                            'name' => $concept->threedmodels->name,
                            'url' => $concept->threedmodels->url,
                            'type' => 'threedmodel',
                            'attachment_id' => $product->id,
                        ]);
                    }
                    
                    // Only use concept reel if it wasn't deleted and user didn't upload a new one
                    if (!empty($concept->reel) && $request->has('concept_reel') && !$request->hasFile('reel')) {
                        $product->update(['reel' => $concept->reel]);
                    }

                    if ($concept->conceptMetalOptions->isNotEmpty()) {
                        ProductMetalOption::insert(
                            $concept->conceptMetalOptions
                                ->map(fn ($option) => [
                                    'product_id' => $product->id,
                                    'metal_id' => $option->metal_id,
                                    'metal_option_id' => $option->metal_option_id,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ])
                                ->all()
                        );
                    }

                    if ($concept->conceptCustomMetalOptions->isNotEmpty()) {
                        ProductCustomMetalOption::insert(
                            $concept->conceptCustomMetalOptions
                                ->map(fn ($customOption) => [
                                    'product_id' => $product->id,
                                    'metal_id' => $customOption->metal_id,
                                    'name' => $customOption->name,
                                    'ref' => $customOption->ref,
                                    'image_url' => $customOption->image_url,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ])
                                ->all()
                        );
                    }
                }
            }

            // Return product ID for file uploads (AJAX request)
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product created successfully. You can now upload files.',
                    'product_id' => $product->id,
                    'redirect_url' => route("{$this->getRoutePrefix()}.products.personalize", $product)
                ]);
            }
            
            // Traditional form submission - redirect to personalization (same concept flow)
            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.products.personalize", $product);


        //return redirect()->back()->with('success','Added successfully ...');
        }catch(\Illuminate\Validation\ValidationException $e){
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        }catch(\Exception $e){
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Probleme while adding product: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error','Probleme while adedding product...');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // Check if user can access this product
        if (!$this->canAccessProduct($product)) {
            abort(403, 'Unauthorized action. You can only view your own products.');
        }

        $product->load([
            'photos', 
            'threedmodels', 
            'rooms', 
            'metals', 
            'category', 
            'user',
            'measure.dimension', 
            'measure.weight'
        ]);
        
        $viewPrefix = $this->getViewPrefix();
        $routePrefix = $this->getRoutePrefix();
        return view("{$viewPrefix}.products.show", [
            'product' => $product,
            'routePrefix' => $routePrefix
        ]);
    }

    /**
     * Show customization page for product submaterials (same flow as concepts).
     */
    public function personalize(Product $product)
    {
        if (!$this->canAccessProduct($product)) {
            abort(403, 'Unauthorized action. You can only personalize your own products.');
        }

        $product->load(['metals.metalOptions', 'productMetalOptions.metalOption', 'productCustomMetalOptions']);

        $selectedOptionsByMetal = $product->productMetalOptions
            ->groupBy('metal_id')
            ->map(fn ($rows) => $rows->pluck('metal_option_id'));

        $customOptionsByMetal = $product->productCustomMetalOptions->groupBy('metal_id');

        $viewPrefix = $this->getViewPrefix();
        $routePrefix = $this->getRoutePrefix();

        return view("{$viewPrefix}.products.personalize", [
            'product' => $product,
            'selectedOptionsByMetal' => $selectedOptionsByMetal,
            'customOptionsByMetal' => $customOptionsByMetal,
            'routePrefix' => $routePrefix,
        ]);
    }

    /**
     * Save product submaterials customization (same logic as concept saveCustomize).
     */
    public function savePersonalize(Request $request, Product $product)
    {
        if (!$this->canAccessProduct($product)) {
            abort(403, 'Unauthorized action. You can only personalize your own products.');
        }

        $product->load('metals');
        $metalIds = $product->metals->pluck('id')->toArray();

        $request->validate([
            'options' => 'nullable|array',
            'options.*' => 'nullable|array',
            'options.*.*' => 'exists:metal_options,id',
            'custom_options' => 'nullable|array',
            'custom_options.*' => 'nullable|array',
            'custom_options.*.*.id' => 'nullable|integer',
            'custom_options.*.*.name' => 'nullable|string|max:120',
            'custom_options.*.*.ref' => 'nullable|string|max:120',
            'custom_options.*.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        ProductMetalOption::where('product_id', $product->id)->delete();

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

                ProductMetalOption::create([
                    'product_id' => $product->id,
                    'metal_id' => $metalId,
                    'metal_option_id' => $metalOptionId,
                ]);
            }
        }

        $existingCustomById = $product->productCustomMetalOptions()->get()->keyBy('id');
        $customEntries = [];
        $customOptions = $request->input('custom_options', []);

        foreach ($customOptions as $metalId => $rows) {
            $metalId = (int) $metalId;
            if (!in_array($metalId, $metalIds, true) || !is_array($rows)) {
                continue;
            }

            foreach ($rows as $index => $row) {
                $row = is_array($row) ? $row : [];
                $existingId = isset($row['id']) ? (int) $row['id'] : null;
                $name = trim((string) ($row['name'] ?? ''));
                $ref = trim((string) ($row['ref'] ?? ''));

                if ($name === '') {
                    continue;
                }

                $uploadedImage = $request->file("custom_options.$metalId.$index.image");
                $imageUrl = null;

                if ($uploadedImage) {
                    $storedPath = $uploadedImage->store('uploads/product-custom-materials', 'public');
                    $imageUrl = '/storage/' . $storedPath;
                } elseif ($existingId && $existingCustomById->has($existingId)) {
                    $imageUrl = $existingCustomById[$existingId]->image_url;
                }

                $customEntries[] = [
                    'product_id' => $product->id,
                    'metal_id' => $metalId,
                    'name' => $name,
                    'ref' => $ref ?: null,
                    'image_url' => $imageUrl,
                ];
            }
        }

        $product->productCustomMetalOptions()->delete();
        if (!empty($customEntries)) {
            ProductCustomMetalOption::insert($customEntries);
        }

        if ($product->status !== 'active') {
            $product->update(['status' => 'active']);
        }

        $routePrefix = $this->getRoutePrefix();
        return redirect()->route("{$routePrefix}.products.show", $product)
            ->with('success', 'Personnalisation enregistrée. Le produit est maintenant actif.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        // Check if user can access this product
        if (!$this->canAccessProduct($product)) {
            abort(403, 'Unauthorized action. You can only edit your own products.');
        }

        $product->load(['photos', 'threedmodels', 'rooms', 'metals', 'category', 'measure.dimension', 'measure.weight']);
        $categories = Category::whereType("main")->get();
        $viewPrefix = $this->getViewPrefix();
        $routePrefix = $this->getRoutePrefix();
        
        return view("{$viewPrefix}.products.edit", [
            'product' => $product,
            'rooms' => Room::all(),
            'metals' => Metal::all(),
            'categories' => $categories,
            'currencies' => Currency::orderBy('name', 'asc')->get(),
            'routePrefix' => $routePrefix
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // Check if user can access this product
        if (!$this->canAccessProduct($product)) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action. You can only update your own products.'
                ], 403);
            }
            abort(403, 'Unauthorized action. You can only update your own products.');
        }

        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'price' => 'required|numeric',
                'size' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'style_type' => 'nullable|in:standard,artisant',
                'rooms' => 'required|array',
                'rooms.*' => 'exists:rooms,id',
                'description' => 'required|string',
                'photos' => 'nullable|array',
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
                'metals' => 'required|array',
                'metals.*' => 'exists:metals,id',
                'currency' => 'required|string',
                'status' => 'required|in:active,inactive',
                'reel' => 'nullable|file|mimes:mp4,mov,ogg,qt,avi,wmv,flv,webm|max:204800',
                'folderModel' => 'nullable|file|mimes:zip|max:51200',
                // Measure fields
                'length' => 'nullable|numeric',
                'height' => 'nullable|numeric',
                'width' => 'nullable|numeric',
                'unit' => 'nullable|in:CM,FT,INCH',
                'measure_size' => 'nullable|in:SMALL,MEDIUM,LARGE',
                'weight_value' => 'nullable|numeric',
                'weight_unit' => 'nullable|in:KG,LB',
                'is_resizable' => 'nullable|boolean',
            ]);

            // Validate that product will have at least one photo after update
            // Count current photos + new photos being uploaded
            $currentPhotosCount = $product->photos()->count();
            $newPhotosCount = $request->hasFile('photos') ? count($request->file('photos')) : 0;
            
            // If no current photos and no new photos being uploaded, reject the update
            if ($currentPhotosCount === 0 && $newPhotosCount === 0) {
                if ($request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Le produit doit avoir au moins une photo.',
                        'errors' => ['photos' => ['Le produit doit avoir au moins une photo. Veuillez télécharger au moins une photo.']]
                    ], 422);
                }
                return redirect()->back()
                    ->withErrors(['photos' => 'Le produit doit avoir au moins une photo. Veuillez télécharger au moins une photo.'])
                    ->withInput();
            }

            // Update basic product fields
            $product->name = $validated['name'];
            $product->price = $validated['price'];
            $product->size = $validated['size'];
            $product->category_id = $validated['category_id'];
            $product->style_type = $request->has('style_type') ? 'artisant' : 'standard';
            $product->description = $validated['description'];
            $product->currency = $validated['currency'];
            $product->status = $validated['status'];
            $product->is_resizable = $request->boolean('is_resizable');
            $product->save();

            // Update rooms
            if ($request->has('rooms')) {
                $product->rooms()->sync($request->rooms);
            }

            // Update metals
            if ($request->has('metals')) {
                $product->metals()->sync($request->metals);
            }

            // Update or create measure
            $measure = $product->measure;
            if (!$measure) {
                $measure = new Measure();
                $measure->product_id = $product->id;
            }

            // Update measure size if provided
            if ($request->has('measure_size') && !empty($request->measure_size)) {
                $measure->size = $request->measure_size;
                $measure->save();
            }

            // Update dimension if provided
            if ($request->has('length') && !empty($request->length)) {
                $dimension = $measure->dimension;
                if (!$dimension) {
                    $dimension = new Dimension();
                    $dimension->measure_id = $measure->id;
                }
                $dimension->length = $request->length;
                $dimension->height = $request->height;
                $dimension->width = $request->width;
                $dimension->unit = $request->unit;
                $dimension->save();
            }

            // Update weight if provided
            if ($request->has('weight_value') && !empty($request->weight_value)) {
                $weight = $measure->weight;
                if (!$weight) {
                    $weight = new Weight();
                    $weight->measure_id = $measure->id;
                }
                $weight->weight_value = $request->weight_value;
                $weight->weight_unit = $request->weight_unit;
                $weight->save();
            }

            // Note: 3D model deletion is handled automatically in uploadModel() method
            // when a new model is uploaded via the separate endpoint

            // Don't handle file uploads here - they should be done via separate endpoints
            // Files are now optional in update to prevent PostTooLargeException

            // Return JSON response for AJAX requests
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully.',
                    'redirect_url' => route("{$this->getRoutePrefix()}.products.personalize", $product)
                ]);
            }

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.products.personalize", $product);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating product: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Problème lors de la mise à jour du produit: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Upload photos for a product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function uploadPhotos(Request $request, Product $product)
    {
        // Check if user can access this product
        if (!$this->canAccessProduct($product)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action. You can only upload photos to your own products.'
            ], 403);
        }

        try {
            // Accept both payload formats used by browsers/clients: photos and photos[]
            $files = $request->file('photos');
            if (!$files) {
                $files = $request->file('photos[]');
            }

            if (!$files) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => ['photos' => ['Le champ photos est requis']]
                ], 422);
            }

            if (!is_array($files)) {
                $files = [$files];
            }

            $validator = Validator::make([
                'photos' => $files,
            ], [
                'photos' => 'required|array|min:1',
                'photos.*' => 'file|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $uploadedPhotos = [];
            foreach ($files as $file) {
                $fileName = uniqid('productPhoto_') . "." . $file->getClientOriginalExtension();
                $file->storeAs('uploads/photos', $fileName, 'public');
                $media = Media::create([
                    'name' => $fileName,
                    'url' => "/storage/uploads/photos/" . $fileName,
                    'attachment_id' => $product->id,
                    'type' => 'product'
                ]);
                $uploadedPhotos[] = $media;
            }

            return response()->json([
                'success' => true,
                'message' => 'Photos uploaded successfully',
                'photos' => $uploadedPhotos
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading photos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload reel for a product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function uploadReel(Request $request, Product $product)
    {
        // Check if user can access this product
        if (!$this->canAccessProduct($product)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action. You can only upload reels to your own products.'
            ], 403);
        }

        try {
            // Log request details for debugging
            \Log::info('Reel upload request', [
                'has_file' => $request->hasFile('reel'),
                'all_files' => array_keys($request->allFiles()),
                'content_type' => $request->header('Content-Type'),
                'content_length' => $request->header('Content-Length'),
                'php_upload_max' => ini_get('upload_max_filesize'),
                'php_post_max' => ini_get('post_max_size'),
                'request_method' => $request->method(),
                'request_all' => $request->all(),
            ]);

            // Check if file is present
            if (!$request->hasFile('reel')) {
                // Check if it's a PostTooLargeException
                if ($request->header('Content-Length') && 
                    (int)$request->header('Content-Length') > $this->parseSize(ini_get('post_max_size'))) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Le fichier est trop volumineux. Limite PHP actuelle: ' . ini_get('post_max_size'),
                        'errors' => ['reel' => ['Fichier trop volumineux pour les limites PHP actuelles']],
                        'debug' => [
                            'has_file' => $request->hasFile('reel'),
                            'all_files' => array_keys($request->allFiles()),
                            'content_type' => $request->header('Content-Type'),
                            'content_length' => $request->header('Content-Length'),
                            'php_upload_max' => ini_get('upload_max_filesize'),
                            'php_post_max' => ini_get('post_max_size'),
                            'parsed_post_max' => $this->parseSize(ini_get('post_max_size')),
                        ]
                    ], 413);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Aucun fichier n\'a été fourni. Vérifiez les limites PHP.',
                    'errors' => ['reel' => ['Le champ reel est requis']],
                    'debug' => [
                        'has_file' => $request->hasFile('reel'),
                        'all_files' => array_keys($request->allFiles()),
                        'content_type' => $request->header('Content-Type'),
                        'content_length' => $request->header('Content-Length'),
                        'php_upload_max' => ini_get('upload_max_filesize'),
                        'php_post_max' => ini_get('post_max_size'),
                        'note' => 'Si Content-Length est présent mais has_file est false, le fichier est probablement trop gros pour les limites PHP'
                    ]
                ], 422);
            }

            $file = $request->file('reel');
            
            // Get file info for debugging
            $fileSize = $file->getSize(); // in bytes
            $fileSizeMB = round($fileSize / 1024 / 1024, 2);
            $mimeType = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();

            // Validate file
            $validated = $request->validate([
                'reel' => 'required|file|mimes:mp4,mov,ogg,qt,avi,wmv,flv,webm|max:204800', // 200MB in KB
            ]);

            // Delete old reel if exists
            if ($product->reel) {
                $oldPath = public_path($product->reel);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Upload new reel
            $fileName = uniqid('productReel_') . "." . $extension;
            $file->storeAs('uploads/reels', $fileName, 'public');
            
            $product->reel = "/storage/uploads/reels/" . $fileName;
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Reel uploaded successfully',
                'reel_url' => $product->reel,
                'file_info' => [
                    'size_mb' => $fileSizeMB,
                    'mime_type' => $mimeType,
                    'extension' => $extension
                ]
            ]);
        } catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Le fichier est trop volumineux. Taille maximale autorisée: 200MB. Veuillez réduire la taille du fichier ou contacter l\'administrateur pour augmenter les limites.',
                'errors' => ['reel' => ['Le fichier dépasse la taille maximale autorisée']]
            ], 413);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
                'debug' => [
                    'file_received' => $request->hasFile('reel'),
                    'php_upload_max' => ini_get('upload_max_filesize'),
                    'php_post_max' => ini_get('post_max_size'),
                ]
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Reel upload error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => [
                    'has_file' => $request->hasFile('reel'),
                    'all_files' => array_keys($request->allFiles()),
                ]
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload du reel: ' . $e->getMessage(),
                'error_type' => get_class($e)
            ], 500);
        }
    }

    /**
     * Upload 3D model for a product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function uploadModel(Request $request, Product $product)
    {
        // Check if user can access this product
        if (!$this->canAccessProduct($product)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action. You can only upload 3D models to your own products.'
            ], 403);
        }

        try {
            $validated = $request->validate([
                'folderModel' => 'required|file|mimes:zip|max:51200',
            ]);

            if ($request->hasFile('folderModel')) {
                // Delete old model if exists
                $existingModel = $product->threedmodels;
                if ($existingModel) {
                    $this->deleteModelFiles($existingModel);
                    $existingModel->delete();
                }

                // Upload and extract new model
                $file = $request->file('folderModel');
                $extension = $file->getClientOriginalExtension();
                
                if ($extension === 'zip') {
                    // Extract ZIP file
                    $extractPath = 'uploads/models/' . uniqid('product3d_');
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
                        $modelUrl = "/storage/" . $extractPath;
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to extract ZIP file'
                        ], 500);
                    }
                } else {
                    // Store GLB/GLTF files directly
                    $fileName = uniqid('product3d_') . "." . $extension;
                    $modelPath = 'uploads/models/' . $fileName;
                    $file->storeAs('uploads/models', $fileName, 'public');
                    $modelUrl = "/storage/uploads/models/" . $fileName;
                }
                
                $media = Media::create([
                    'name' => basename($modelPath),
                    'url' => $modelUrl,
                    'attachment_id' => $product->id,
                    'type' => 'threedmodel'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => '3D model uploaded successfully',
                    'model' => $media
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No file provided'
            ], 422);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading 3D model: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    /**
     * Delete a specific photo from a product
     */
    public function deletePhoto(Product $product, $photoId)
    {
        // Check if user can access this product
        if (!$this->canAccessProduct($product)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action. You can only delete photos from your own products.'
            ], 403);
        }

        try {
            $photo = Media::where('id', $photoId)
                ->where('attachment_id', $product->id)
                ->where('type', 'product')
                ->first();

            if (!$photo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Photo not found or does not belong to this product.'
                ], 404);
            }

            // Delete the file from storage if it exists
            if ($photo->url && \Storage::disk('public')->exists($photo->url)) {
                \Storage::disk('public')->delete($photo->url);
            }

            // Delete the database record
            $photo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Photo deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting photo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Product $product)
    {
        // Check if user can access this product
        if (!$this->canAccessProduct($product)) {
            abort(403, 'Unauthorized action. You can only delete your own products.');
        }

        try{
                // Delete 3D model files if exists
                $existingModel = $product->threedmodels;
                if ($existingModel) {
                    $this->deleteModelFiles($existingModel);
                }
                
                $product->delete();
                return redirect()->back()->with('success','Product'.$product->name." deleted successfully ...");
        }catch( \Exception $e  ){
            return redirect()->back()->with('error','Error while trying to delete Product'.$product->name);

        }
    }

    /**
     * Parse PHP size string (e.g., "8M", "2M") to bytes
     */
    private function parseSize($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
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
