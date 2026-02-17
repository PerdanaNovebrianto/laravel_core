<?php

namespace App\Repositories;

use App\Models\Profile;

class ProfileRepository
{
    public function getByUserId($id)
    {
        return Profile::where('user_id', $id)->first();
    }

    public function create(array $data)
    {
        return Profile::create($data);
    }

    public function update(array $data, $id)
    {
        $profile = Profile::find($id);
        $profile->update($data);

        return $profile->refresh();
    }
}