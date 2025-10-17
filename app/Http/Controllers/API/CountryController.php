<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response; // Optional, but good practice
class CountryController extends Controller
{
    public function countries()
    {

        try{
            $countries = Country::with("currency")->get();
            return Response::json($countries,200);
        }catch(\Exception $e){
            return Response::json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllCurrencies(){

        try{
            $currencies = Currency::all();
            return Response::json($currencies,200);
        }catch(\Exception $e){
            return Response::json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
