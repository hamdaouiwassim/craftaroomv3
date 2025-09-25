<?php

namespace App\Http\Controllers\API;

use Auth;
use Hash;
use Validator;
use Laravel\Passport\Token;
use App\Models\User;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

class UserController extends BaseController
{
    //

    public function products(Request $request){
        try{
            $user = $this->getUserFromToken($request);

            if ($user){
                return $this->okResponse(Product::with('threedmodels','photos','rooms','metals','category','measure','measure.dimension','measure.weight','user')->get(),"Product List ...");
             }
            else { return $this->errorResponse('error',['error' => 'Can\'t found user'],404); }
        }catch(Exception $e){
            return $this->errorResponse('error',['error' => $e],500);
        }

    }

    public function updateUser(Request $request){
        try{
            $user = $this->getUserFromToken($request);

            if ($user){

            (!empty($request->name)) && $user->name = $request->name;
           (!empty($request->country)) && $user->country = $request->country;
           (!empty($request->phone)) && $user->phone = $request->phone;
           (!empty($request->adress)) && $user->adress = $request->adress;
           if ( $request->hasFile('photoUrl') ){
            $validator = Validator::make($request->all(),[
                "photoUrl" => "required|mimes:jpeg,png,jpg|max:2024",
            ]);

            if ($validator->fails()){
                return $this->errorResponse(Arr::first(Arr::flatten($validator->messages()->get('*'))),[],401);
            }
            $filePath = $request->file('photoUrl');
            $fileName = uniqid('avatar_').".". $filePath->getClientOriginalExtension();
            $filePath->storeAs('uploads/avatars/', $fileName, 'public');

            Media::create([
                'name' => $fileName,
                'url' => "/storage/uploads/avatars/" .$fileName,
                'attachment_id'=> $user->id,
                'type' => 'avatar'
            ]);

            $user->photoUrl = "/storage/uploads/avatars/" .$fileName;

           }
           $user->update();
                return $this->okResponse($user,"User updated successfully ...");
             }
            else { return $this->errorResponse('Can\'t found user',[],404); }
        }catch(Exception $e){
            return $this->errorResponse($e->getMessage(),[],500);
        }
    }

    public function updatePassword(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "oldPass" => "required|min:8",
                "newPass" => "required|min:8",
            ]);

            if ($validator->fails()){
                return $this->errorResponse(Arr::first(Arr::flatten($validator->messages()->get('*'))),[],401);
            }
            $user = $this->getUserFromToken($request);


            if ($user){
                if ( Hash::check($request->oldPass, $user->password) ){
                    $user->password = Hash::make($request->newPass);
                    $user->update();
                    return $this->okResponse($user,"Password updated successfully ...");
                }else{
                    return $this->errorResponse('Old password are wrong',[],401);
                }

             }
            else { return $this->errorResponse('Can\'t Update User',[],401); }
        }catch(Exception $e){
            return $this->errorResponse($e->getMessage(),[],500);
        }
    }

private function getUserFromToken($request){
    // Passport middleware will already resolve the user if the token is valid
    if (Auth::check()) {
        return Auth::user();
    }

    // If you want to manually parse the token from request header:
    $token = $request->bearerToken();
    if (!$token) {
        return null;
    }

    $user = Auth::guard('api')->user(); // get user from passport guard
    return $user;
}


}
