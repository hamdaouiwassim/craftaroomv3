<?php

namespace App\Http\Controllers\API;

use Auth;
use Hash;
use Validator;
use App\Models\User;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

class ProductController extends BaseController
{
    //

    public function index(Request $request){


        
       

        try{

            $products = Product::with('threedmodels','photos','rooms','metals','category','measure','measure.dimension','measure.weight','user')->get();
                
            return $this->okResponse($products,"List of products ...");

        }catch(Exception $e){
            return $this->errorResponse('error',['error' => $e],500);
        }

     

      

        
    }


    

}
