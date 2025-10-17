<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'loginType' => $this->loginType,
            'type' => $this->type,
            'phone' => $this->phone,
            'photoUrl' => $this->photoUrl,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Add other fields as necessary
            "currency" => CurrencyResource::make($this->currency),
            "address" => AddressResource::make($this->address),
        ];
    }
}
