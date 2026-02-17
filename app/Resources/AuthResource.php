<?php

namespace App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class AuthResource extends JsonResource
{

    
    public function toArray(Request $request): array
    {
        if($request->routeIs('auth.login')) {
            return [
                'id'      => Hashids::encode($this['user']->id),
                'email'   => $this['user']->email,
                'role'    => $this['user']->role ? [
                    'name'       => $this['user']->role->name,
                    'privileges' => explode(',', $this['user']->role->privileges),
                ] : null,
                'profile' => $this['user']->profile ? [
                    'name'  => $this['user']->profile->name,
                    'photo' => $this['user']->profile->photo,
                ] : null,
                'access_token'  => $this['access_token'],
                'refresh_token' => $this['refresh_token'],
            ];
        }

        if($request->routeIs('auth.refreshToken')) {
            return [
                'access_token'  => $this['access_token'],
                'refresh_token' => $this['refresh_token'],
            ];
        }
    }
}