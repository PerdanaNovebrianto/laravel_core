<?php

namespace Database\Seeders;

use App\Models\Privilege;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrivilegeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Privilege::truncate();
        $privileges = [
            ['name' => 'role-get', 'group' => 'Role'],
            ['name' => 'role-detail', 'group' => 'Role'],
            ['name' => 'role-create', 'group' => 'Role'],
            ['name' => 'role-update', 'group' => 'Role'],
            ['name' => 'role-delete', 'group' => 'Role'],
            ['name' => 'user-get', 'group' => 'User'],
            ['name' => 'user-detail', 'group' => 'User'],
            ['name' => 'user-create', 'group' => 'User'],
            ['name' => 'user-update', 'group' => 'User'],
            ['name' => 'user-delete', 'group' => 'User'],
        ];

        foreach($privileges as $privilege){
            Privilege::create($privilege);
        }
    }
}
