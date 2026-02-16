<?php

namespace App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'    => Hashids::encode($this->id),
            'email' => $this->email,
            'name'  => $this->profile->name,
            'phone' => $this->profile->phone,
            'photo' => $this->profile->photo,
        ];
    }
}