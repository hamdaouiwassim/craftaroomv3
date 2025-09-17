<?php

namespace App\Http\Controllers\API;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
class MaterialController extends BaseController
{
    //
    public function index(){
        try{
           
                return $this->okResponse(Material::with('photo')->get(),"Material List ...");
           
        }catch(Exception $e){
            return $this->errorResponse('error',['error' => $e],500);
        }
     
    }
}
