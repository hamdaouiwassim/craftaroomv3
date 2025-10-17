<?php

namespace App;

use Auth;

trait ConnectedUser
{
    //
     private function getUserFromToken($request)
    {
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
