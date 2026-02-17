<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository
{
    public function getAll()
    {
        return Role::all();
    }

    public function getByName($name)
    {
        return Role::where('name', $name)->first();
    }

    public function create(array $data)
    {
        return Role::create($data);
    }

    public function update(array $data, $id)
    {
        $role = Role::find($id);
        $role->update($data);
        return $role->refresh();
    }

    public function delete($id)
    {
        return Role::find($id)->delete();
    }
}