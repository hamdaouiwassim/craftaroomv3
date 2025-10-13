<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response; // Optional, but good practice
class CountryController extends Controller
{
    public function countries()
    {

        try{


       // 1. Define the full path to the file
        $path = resource_path('files/countries.json');

        // 2. Check if the file exists
        if (!File::exists($path)) {
            return Response::json([
                'error' => 'File not found.',
                'path' => $path
            ], 404);
        }

        // 3. Read the file content
        $jsonContent = File::get($path);

        // 4. Decode the JSON content
        $data = json_decode($jsonContent, true);

        // Check for JSON decoding errors (optional but recommended)
        if (json_last_error() !== JSON_ERROR_NONE) {
            return Response::json([
                'error' => 'Invalid JSON format in file.',
                'json_error' => json_last_error_msg()
            ], 500);
        }

        // 5. Return the data as a JSON response
        return Response::json($data);
        }catch(\Exception $e){
            return Response::json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllCurrencies(){

        try{
// 1. Define the full path and read the file
        $path = resource_path('files/countries.json'); // Adjust the filename if needed

        if (!File::exists($path)) {
            return Response::json(['error' => 'Countries data file not found.'], 404);
        }

        $jsonContent = File::get($path);
        $countries = json_decode($jsonContent, true);

        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($countries)) {
            return Response::json(['error' => 'Invalid JSON format in file.'], 500);
        }

        $currencies = [];

        // 2. Iterate through the countries and extract the currency
        foreach ($countries as $country) {
            // Ensure the 'currency' key exists before accessing it
            if (isset($country['currency'])) {
                $currencyData = $country['currency'];
                $currencyId = $currencyData['id'];

                // 3. Store the currency using its 'id' as the key to ensure uniqueness
                // This prevents duplicates if multiple countries share the same currency.
                $currencies[$currencyId] = $currencyData;
            }
        }

        // 4. Return the unique currencies as a list (resetting array keys)
        return Response::json(array_values($currencies));
        }catch(\Exception $e){
            return Response::json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
