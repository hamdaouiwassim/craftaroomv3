<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Room;
use App\Models\Media;
use App\Models\Metal;
use App\Models\Weight;
use App\Models\Measure;
use App\Models\Product;
use App\Models\Category;
use App\Models\Dimension;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::query();

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
        return view('admin.products.index', ["products" => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = Category::whereType("main")->get();
        return view('admin.products.create',[
            'categories' => $categories,
            'rooms'=>Room::all() ,
            'metals'=>Metal::all(),
            'currencies'=> Currency::all()
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
                'size' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'rooms' => 'required|array',
                'rooms.*' => 'exists:rooms,id',
                'description' => 'required|string',
                'folderModel' => 'required|file',
                'photos' => 'required|array',
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'metals' => 'required|array',
                'metals.*' => 'exists:metals,id',
                'currency' => 'required|string',
                'status' => 'nullable|in:active,inactive',
            ]);

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
                return redirect()->back()->with('error','Please add product measure...');
            }

            $inputs = $request->all();
            $inputs['user_id'] = auth()->user()->id;
        // upload Reel
        if($request->hasFile('reel')){
            $filePath = $request->file('reel');
            $fileName = uniqid('productReel_').".". $filePath->getClientOriginalExtension();
            $filePath->storeAs('uploads/reels', $fileName, 'public');

            $inputs['reel']= "/storage/uploads/reels/" .$fileName;
         }
        $product = Product::create($inputs);

        foreach($request->rooms as $room){
            $product->rooms()->attach($room);
        }
        foreach($request->metals as $metal){
            $product->metals()->attach($metal);
        }

         if ($request->has('folderModel') && !empty($request->folderModel) )
         {
                        // $validated = $request->validate([
                        //     'folderModel' => 'zip'
                        // ]);

                        // upload 3D Model
                        $filePath = $request->file('folderModel');
                        $fileName = uniqid('product3d_').".". $filePath->getClientOriginalExtension();
                        $filePath->storeAs('uploads/models', $fileName, 'public');

                        Media::create([
                            'name' => $fileName,
                            'url' => "/storage/uploads/models/" .$fileName,
                            'attachment_id'=> $product->id,
                            'type' => 'threedmodel'
                        ]);
            }
            // upload Photos
            if($request->hasFile('photos'))
            {
                $files = $request->file('photos');
                foreach($files as $file){
                    $fileName = uniqid('productPhoto_').".". $file->getClientOriginalExtension();
                    $file->storeAs('uploads/photos', $fileName, 'public');
                    Media::create([
                        'name' => $fileName,
                        'url' => "/storage/uploads/photos/" .$fileName,
                        'attachment_id'=> $product->id,
                        'type' => 'product'
                    ]);
                }
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

            return redirect('/'.strtolower(auth()->user()->role).'/myproducts')->with('success','Product added successfully ...');


        //return redirect()->back()->with('success','Added successfully ...');
        }catch(\Exception $e){
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
        
        return view('admin.products.show', [
            'product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $product->load(['photos', 'threedmodels', 'rooms', 'metals', 'category', 'measure.dimension', 'measure.weight']);
        $categories = Category::whereType("main")->get();
        
        return view('admin.products.edit', [
            'product' => $product,
            'rooms' => Room::all(),
            'metals' => Metal::all(),
            'categories' => $categories,
            'currencies' => Currency::all()
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
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'price' => 'required|numeric',
                'size' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'rooms' => 'required|array',
                'rooms.*' => 'exists:rooms,id',
                'description' => 'required|string',
                'photos' => 'nullable|array',
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
                'metals' => 'required|array',
                'metals.*' => 'exists:metals,id',
                'currency' => 'required|string',
                'status' => 'required|in:active,inactive',
                'reel' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:102400',
                'folderModel' => 'nullable|file|mimes:zip|max:51200',
                'delete_3d_model' => 'nullable|boolean',
            ]);

            // Update basic product fields
            $product->name = $validated['name'];
            $product->price = $validated['price'];
            $product->size = $validated['size'];
            $product->category_id = $validated['category_id'];
            $product->description = $validated['description'];
            $product->currency = $validated['currency'];
            $product->status = $validated['status'];

            // Update rooms
            if ($request->has('rooms')) {
                $product->rooms()->sync($request->rooms);
            }

            // Update metals
            if ($request->has('metals')) {
                $product->metals()->sync($request->metals);
            }

            // Upload new reel if provided
            if ($request->hasFile('reel')) {
                $filePath = $request->file('reel');
                $fileName = uniqid('productReel_') . "." . $filePath->getClientOriginalExtension();
                $filePath->storeAs('uploads/reels', $fileName, 'public');
                $product->reel = "/storage/uploads/reels/" . $fileName;
            }

            // Handle 3D Model deletion and replacement
            if ($request->has('delete_3d_model') && $request->delete_3d_model == '1') {
                // Delete existing 3D model
                $existingModel = $product->threedmodels;
                if ($existingModel) {
                    // Delete physical file
                    $filePath = public_path($existingModel->url);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    // Delete database record
                    $existingModel->delete();
                }
            }

            // Upload new 3D model if provided
            if ($request->hasFile('folderModel')) {
                // Delete old model if exists
                $existingModel = $product->threedmodels;
                if ($existingModel) {
                    $filePath = public_path($existingModel->url);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $existingModel->delete();
                }

                // Upload new model
                $filePath = $request->file('folderModel');
                $fileName = uniqid('product3d_') . "." . $filePath->getClientOriginalExtension();
                $filePath->storeAs('uploads/models', $fileName, 'public');
                
                Media::create([
                    'name' => $fileName,
                    'url' => "/storage/uploads/models/" . $fileName,
                    'attachment_id' => $product->id,
                    'type' => 'threedmodel'
                ]);
            }

            $product->save();

            // Upload new photos if provided
            if ($request->hasFile('photos')) {
                // Optionally delete old photos or keep them
                // For now, we'll add new photos without deleting old ones
                $files = $request->file('photos');
                foreach ($files as $file) {
                    $fileName = uniqid('productPhoto_') . "." . $file->getClientOriginalExtension();
                    $file->storeAs('uploads/photos', $fileName, 'public');
                    Media::create([
                        'name' => $fileName,
                        'url' => "/storage/uploads/photos/" . $fileName,
                        'attachment_id' => $product->id,
                        'type' => 'product'
                    ]);
                }
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Produit mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Problème lors de la mise à jour du produit: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
        try{
                $product->delete();
                return redirect()->back()->with('success','Product'.$product->name." deleted successfully ...");
        }catch( \Exception $e  ){
            return redirect()->back()->with('error','Error while trying to delete Product'.$product->name);

        }
    }
}
