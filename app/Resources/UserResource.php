<?php

namespace App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Vinkla\Hashids\Facades\Hashids;

class UserResource extends JsonResource
{
    private $profile_path;
    private $ui_avatar;

    public function __construct($resource){
        parent::__construct($resource);
        $this->profile_path = config('services.asset.url').'/image/profile';
        $this->ui_avatar = 'https://ui-avatars.com/api/?format=png&bold=true&background=dfdfff&name=';
    }

    public function toArray(Request $request): array
    {
        if($request->routeIs('user.all')) {
            return [
                'id'    => Hashids::encode($this->id),
                'email' => $this->email,
                'role'  => $this->role->name,
                'name'  => $this->profile?->name,
            ];
        }

        if($request->routeIs('user.detail', 'user.update')) {
            return [
                'id'    => Hashids::encode($this->id),
                'email' => $this->email,
                'role'  => $this->role->name,
                'name'  => $this->profile?->name,
                'phone' => $this->profile?->phone,
                'photo' => $this->profile
                    ? ($this->profile->photo
                        ? url($this->profile_path . '/' . $this->profile->photo)
                        : $this->ui_avatar . urlencode($this->profile->name))
                    : null,
            ];
        }
    }
}