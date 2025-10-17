<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Media;
use DB;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

         if (auth()->user()->is_admin()) {
        return view("admin.categories.index",[
            'categories' => Category::whereType("main")->paginate(perPage: 3)]);
         }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = Category::whereType('main')->whereStatus('active')->get();
        if (auth()->user()->is_admin()) {
            return view("admin.categories.create" , [
                'categories' => $categories
            ]);
        }

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:main,sub',
            'category_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        DB::beginTransaction();
        try{

                $category = Category::create($validated);
                if ($request->hasFile('icon')) {
                    $iconPath = $request->file('icon');
                    $iconName = uniqid('avatar_') . "." . $iconPath->getClientOriginalExtension();
                    $iconPath->storeAs('uploads/category_icons/', $iconName, 'public');

                    // Assuming you have a Media model to handle media files
                    $media = new Media();
                    $media->name = $request->name."_Icon";
                    $media->url = "/storage/uploads/category_icons/" . $iconName;
                    $media->attachment_id = $category->id;
                    $media->type = "category";
                    $media->save();
                }
                DB::commit();
                return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');


        }catch(\Exception $e){
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'An error occurred while saving the category. Please try again.', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
        return view("admin.categories.show",[
            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
        if (!auth()->user()->is_admin()) {
            abort(403, 'Unauthorized action.');
        }
         $categories = Category::whereType('main')->whereStatus('active')->get();
        return view("admin.categories.edit",[
            'category' => $category ,'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
          $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:main,sub',
            'category_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        DB::beginTransaction();
        try{

                //$category = Category::create($validated);
                if ($request->hasFile('icon')) {
                    $category->icon()->delete();
                    $iconPath = $request->file('icon');
                    $iconName = uniqid('avatar_') . "." . $iconPath->getClientOriginalExtension();
                    $iconPath->storeAs('uploads/category_icons/', $iconName, 'public');

                    // Assuming you have a Media model to handle media files
                    $media = new Media();
                    $media->name = $request->name."_Icon";
                    $media->url = "/storage/uploads/category_icons/" . $iconName;
                    $media->attachment_id = $category->id;
                    $media->type = "category";
                    $media->save();

                }
                $category->update($validated);


                DB::commit();
                return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');


        }catch(\Exception $e){
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'An error occurred while updating the category. Please try again.', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
        try {
            $category->delete();
            return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while deleting the category. Please try again.', 'message' => $e->getMessage()]);
        }
    }
}
