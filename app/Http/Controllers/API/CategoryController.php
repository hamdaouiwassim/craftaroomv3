<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Category;

class CategoryController extends BaseController
{


    public function index(){
        try{

            $categories = Category::whereType("main")->with("sub_categories")->get();
            return $this->okResponse($categories , 'Categories retrieved successfully.',200);

        }catch(\Exception $e){
            return $this->errorResponse('error',['error' => $e->getMessage()],500);

        }
    }
}
