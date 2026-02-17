<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'admin@admin.com',
                'password' => Hash::make('Admin123'),
                'role_id' => Role::where('name', 'Super Admin')->first()->id,
            ],
        ];

        foreach($users as $user){
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'password' => $user['password'],
                    'role_id'  => $user['role_id'],
                ]
            );
        }
    }
}
