<?php

namespace App\Http\Controllers\API;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
class RoomController extends BaseController
{
    //
    public function index(){
        try{
          
                return $this->okResponse(Room::all(),"Room List ...");
          
        }catch(Exception $e){
            return $this->errorResponse('error',['error' => $e],500);
        }
     
    }
    public function withProducts(){
        try{
       
                return $this->okResponse(Room::with('products')->get(),"Room List ...");
        
        }catch(Exception $e){
            return $this->errorResponse('error',['error' => $e],500);
        }
     
    }
}
