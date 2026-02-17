<?php

namespace App\Repositories;

use App\Models\Privilege;

class PrivilegeRepository
{
    public function getAll()
    {
        return Privilege::all();
    }

    public function getByGroup($group)
    {
        return Privilege::where('group', $group)->get();
    }

    public function create(array $data)
    {
        return Privilege::create($data);
    }

    public function update(array $data, $id)
    {
        $privilege = Privilege::find($id);
        $privilege->update($data);
        return $privilege->refresh();
    }

    public function delete($id)
    {
        return Privilege::find($id)->delete();
    }
}