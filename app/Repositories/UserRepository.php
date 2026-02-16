<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAll()
    {
        return User::all();
    }

    public function getByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function getById($id)
    {
        return User::find($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(array $data, $id)
    {
        return User::find($id)->update($data);
    }

    public function delete($id)
    {
        return User::find($id)->delete();
    }
}