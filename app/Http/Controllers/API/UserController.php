<?php

namespace App\Http\Controllers\API;

use App\ConnectedUser;
use App\Http\Resources\UserResource;
use Auth;
use DB;
use Hash;
use Illuminate\Support\Arr;
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

    use ConnectedUser;

    public function products(Request $request)
    {
        try {
            $user = $this->getUserFromToken($request);

            if ($user) {
                return $this->okResponse(Product::with('threedmodels', 'photos', 'rooms', 'metals', 'category', 'measure', 'measure.dimension', 'measure.weight', 'user')->get(), "Product List ...");
            } else {
                return $this->errorResponse('error', ['error' => 'Can\'t found user'], 404);
            }
        } catch (Exception $e) {
            return $this->errorResponse('error', ['error' => $e], 500);
        }

    }

    public function updateUser(Request $request)
    {

        try {
            DB::beginTransaction();
            $user = $this->getUserFromToken($request);
            $validator = Validator::make($request->all(), [
                "name" => "sometimes|string|max:255",
                "phone" => "sometimes|string|max:20",
                "role" => "sometimes|int|in:1,2,3",
                "photoUrl" => "sometimes|mimes:jpeg,png,jpg|max:2024",
                "currency_id" => "sometimes|exists:currencies,id",
                "language" => "sometimes|int|in:0,1,2",
            ]);

            if ($validator->fails()) {
                return $this->errorResponse(Arr::first(Arr::flatten($validator->messages()->get('*'))), [], 422);
            }



            if ($user) {

                (!empty($request->name)) && $user->name = $request->name;
                (!empty($request->country)) && $user->country = $request->country;
                (!empty($request->phone)) && $user->phone = $request->phone;
                (!empty($request->adress)) && $user->adress = $request->adress;
                (!empty($request->role)) && $user->role = $request->role;
                (!empty($request->currency_id)) && $user->currency_id = $request->currency_id;
                (!empty($request->language)) && $user->language = $request->language;
                if ($request->hasFile('photoUrl')) {

                    $filePath = $request->file('photoUrl');
                    $fileName = uniqid('avatar_') . "." . $filePath->getClientOriginalExtension();
                    $filePath->storeAs('uploads/avatars/', $fileName, 'public');

                    Media::create([
                        'name' => $fileName,
                        'url' => "/storage/uploads/avatars/" . $fileName,
                        'attachment_id' => $user->id,
                        'type' => 'avatar'
                    ]);

                    $user->photoUrl = "/storage/uploads/avatars/" . $fileName;

                }
                $user->update();
                 DB::commit();
                return $this->okResponse(UserResource::make($user), "User updated successfully ...");

            } else {
                return $this->errorResponse('Can\'t found user', [], 404);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "oldPass" => "required|min:8",
                "newPass" => "required|min:8",
            ]);

            if ($validator->fails()) {
                return $this->errorResponse(Arr::first(Arr::flatten($validator->messages()->get('*'))), [], 401);
            }
            $user = $this->getUserFromToken($request);


            if ($user) {
                if (Hash::check($request->oldPass, $user->password)) {
                    $user->password = Hash::make($request->newPass);
                    $user->update();
                    return $this->okResponse($user, "Password updated successfully ...");
                } else {
                    return $this->errorResponse('Old password are wrong', [], 401);
                }

            } else {
                return $this->errorResponse('Can\'t Update User', [], 401);
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }

    public function updateAddress(Request $request)
    {
        try {
            $user = $this->getUserFromToken($request);
            $validator = Validator::make($request->all(), [
                "address_line1" => "sometimes|string|max:255",
                "address_line2" => "sometimes|string|max:255",
                "city" => "sometimes|string|max:100",
                "state" => "sometimes|string|max:100",
                "postal_code" => "sometimes|string|max:20",
                "country" => "sometimes|string|max:100"
            ]);

            if ($validator->fails()) {
                return $this->errorResponse(Arr::first(Arr::flatten($validator->messages()->get('*'))), [], 422);
            }

            if ($user) {

                $address = $user->address;
                if ($address) {
                    // Update existing address
                    (!empty($request->address_line1)) && $address->address_line1 = $request->address_line1;
                    (!empty($request->address_line2)) && $address->address_line2 = $request->address_line2;
                    (!empty($request->city)) && $address->city = $request->city;
                    (!empty($request->state)) && $address->state = $request->state;
                    (!empty($request->postal_code)) && $address->postal_code = $request->postal_code;
                    (!empty($request->country)) && $address->country = $request->country;
                    $address->update();
                } else {
                    // Create new address
                    $addressData = $request->only(['address_line1', 'address_line2', 'city', 'state', 'postal_code', 'country']);
                    $addressData['user_id'] = $user->id;
                    $address = \App\Models\Address::create($addressData);
                }

                return $this->okResponse($address, "Address updated successfully ...");
            } else {
                return $this->errorResponse('Can\'t found user', [], 404);
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }

    }








}
