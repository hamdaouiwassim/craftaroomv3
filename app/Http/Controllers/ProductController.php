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
    public function index()
    {
        //
        $products = Product::whereStatus("active")->paginate(10);
        return view('admin.products.index',["products"=>$products]);
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
                'price' => 'required',
                'size' => 'required',
                'category_id' => 'required',
                'rooms' => 'required',
                'description' => 'required',
                'folderModel' => 'required',
                'photos' => 'required',
                'metals' => 'required',
                'currency' => 'required',


            ]);

            $m = false;
            $measure = new Measure();
            //$measure->save();
            if (  $request->has("length") && !empty($request->length) ) {
                $validated = $request->validate([
                    'length' => 'required',
                    'height' => 'required',
                    'width' => 'required',
                    'unit' => 'required',

                ]);
                    $dimension = new Dimension();
                    $dimension->length = $request->length;
                    $dimension->height = $request->height;
                    $dimension->width = $request->width;
                    $dimension->unit = $request->unit;
                    $dimension->measure_id = $request->unit;
                    $m = true;
                    //$dimension->save();
            }
            if (  $request->has("size") && !empty($request->size) ) {
                $measure->size = $request->size;
                $m = true;
            }
            if (  $request->has("weight_value") && !empty($request->weight_value) ) {
                $validated = $request->validate([
                    'weight_value' => 'required',
                    'weight_unit' => 'required',

                ]);
                $weight = new Weight();
                $weight->weight_value = $request->weight_value;
                $weight->weight_unit = $request->weight_unit;
                $weight->measure_id = $request->unit;
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
                echo "photos here ...";
                  // dd($request);
                    $files = $request->file('photos');
                    //dd($files);
                    foreach($files as $index=> $file){
                        echo "file ".$index;
                    $fileName = uniqid('productPhoto_').".". $file->getClientOriginalExtension();
                    $extension = $file->getClientOriginalExtension();


                        echo "Photo ok ...";
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
        }catch(Exception $e){
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
        return view(strtolower(auth()->user()->role).'.products.edit',['product'=> $product,'rooms'=>Room::all() ,'metals'=>Metal::all() , 'categories' => Category::all()]);
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
           // upload Reel
           if($request->hasFile('reel')){
            $filePath = $request->file('reel');
            $fileName = uniqid('productReel_').".". $filePath->getClientOriginalExtension();
            $filePath->storeAs('uploads/reels', $fileName, 'public');

           $product->reel = "/storage/uploads/reels/" .$fileName;
           $product->update();
         }
         return redirect()->back();
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
        }catch( Exception $e  ){
            return redirect()->back()->with('error','Error while trying to delete Product'.$product->name);

        }
    }
}
