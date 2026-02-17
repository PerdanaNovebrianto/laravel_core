<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'privileges' => implode(',', array(
                        'role-get',
                        'role-detail',
                        'role-create',
                        'role-update',
                        'role-delete',
                        'user-get',
                        'user-detail',
                        'user-create',
                        'user-update',
                        'user-delete',
                    )
                )
            ],
            [
                'name' => 'User',
                'privileges' => implode(',', array(
                        'user-detail',
                        'user-update',
                    )
                )
            ],
        ];

        foreach($roles as $role){
            Role::updateOrCreate(
                ['name' => $role['name']],
                ['privileges' => $role['privileges']]
            );
        }
    }
}
