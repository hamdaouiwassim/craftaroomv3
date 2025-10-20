<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends BaseController
{


    public function index($status="active"){
        try{

            $categories = Category::whereType("main")->whereStatus($status)->with("sub_categories","sub_categories.icon","icon")->get();
            return $this->okResponse(CategoryResource::collection($categories) , 'Categories retrieved successfully.',200);

        }catch(\Exception $e){
            return $this->errorResponse('error',['error' => $e->getMessage()],500);

        }
    }
}
