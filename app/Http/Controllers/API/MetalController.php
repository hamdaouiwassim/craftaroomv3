<?php

namespace App\Http\Controllers\API;

use App\Models\Metal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
class MetalController extends BaseController
{
    //
    public function index(){
        try{
      
                return $this->okResponse(Metal::all(),"Metal List ...");
          
        }catch(Exception $e){
            return $this->errorResponse('error',['error' => $e],500);
        }
     
    }
    public function withProducts(){
        try{
        
                return $this->okResponse(Metal::with('products')->get(),"Metal List ...");
            
        }catch(Exception $e){
            return $this->errorResponse('error',['error' => $e],500);
        }
     
    }
}
