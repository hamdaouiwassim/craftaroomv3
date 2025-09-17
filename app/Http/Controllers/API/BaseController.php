<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
class BaseController extends Controller
{
    //

    public function okResponse($result , $message ){


        $response = [
            "success" => true,
            "data" => $result,
            "message" => $message

        ];
        return response()->json($response, 200);
    }

    public function errorResponse($error,$errorMessages = [], $code = 401 ){

        $response = [

            "success" => false,
            "message" => $error

        ];
        if(!empty($errorMessages)){
            $response["data"] = $errorMessages;

        }
        return response()->json($response, $code);

    }
}
