<?php

namespace App\Http\Controllers\API;

use Auth;
use Hash;
use Validator;
use App\Models\User;
use App\Models\Media;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

class AuthController extends BaseController
{
    //

    public function register(Request $request){



        $validator = Validator::make($request->all(),[
            "loginType"=> "required",
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:8",
            "c_password" => "required|same:password",
            "role" => "required",
        ]);

        if ($validator->fails()){
            return $this->errorResponse(Arr::first(Arr::flatten($validator->messages()->get('*'))),[],401);
        }
        try{
            $inputs = $request->all();
            $inputs["password"] = Hash::make($request->password);
            //dd();
            $newUser = User::create($inputs);
            $success["token"] = $newUser->createToken("MyApp")->accessToken;
            $success["user"] = $newUser;

            if ($request->loginType != "standart"){
                if ($request->photoUrl){
                    Media::create([
                        'name' => uniqid("avatar_").$newUser->id,
                        'url' => $request->photoUrl,
                        'attachment_id'=> $newUser->id,
                        'type' => 'avatar'
                    ]);
                }
            }
            return $this->okResponse($success,"User Created Successfully ...");

        }catch(Exception $e){
            return $this->errorResponse('error',['error' => $e],500);
        }
    }


   public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        "email" => "required|email",
        "password" => "required|min:8",
        "loginType" => "required"
    ]);

    if ($validator->fails()) {
        return $this->errorResponse(
            "Validation error",
            Arr::first(Arr::flatten($validator->messages()->get('*'))),
            401
        );
    }

    // Social login
    if ($request->loginType === "facebook" || $request->loginType === "google" || $request->loginType === "apple") {

        if ($request->loginType === "facebook") {
            $user = User::where('email', $request->email)
                        ->where('facebook_id', $request->password)
                        ->first();
        } else if ($request->loginType === "google") {
            $user = User::where('email', $request->email)
                        ->where('google_id', $request->password)
                        ->first();
        }else {
            $user = User::where('email', $request->email)
                        ->where('apple_id', $request->password)
                        ->first();
        }

        if ($user) {
            // Directly create a token without logging in
            $success["token"] = $user->createToken("authToken")->accessToken;
            $success["user"] = $user;
            return $this->okResponse($success, "User login Successfully ...");
        }

        return $this->errorResponse("Invalid email or password", [], 401);
    }

    // Normal email/password login
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $user = Auth::user();
        $success["token"] = $user->createToken("authToken")->accessToken;
        $success["user"] = $user;
        return $this->okResponse($success, "User login Successfully ...");
    }

    return $this->errorResponse("Invalid email or password", [], 401);
}


}

