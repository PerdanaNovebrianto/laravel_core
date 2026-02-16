<?php

namespace App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class AuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->when($request->routeIs('auth.login'), fn () => Hashids::encode($this['user']->id)),
            'name'  => $this->when($request->routeIs('auth.login'), fn () => $this['user']->profile->name),
            'photo' => $this->when($request->routeIs('auth.login'), fn () => $this['user']->profile->photo),
            'email' => $this->when($request->routeIs('auth.login'), fn () => $this['user']->email),
            'access_token' => $this->when($request->routeIs('auth.login', 'auth.refreshToken'), fn () => $this['access_token']),
            'refresh_token' => $this->when($request->routeIs('auth.login', 'auth.refreshToken'), fn () => $this['refresh_token']),
        ];
    }
}