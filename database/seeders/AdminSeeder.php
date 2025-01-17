<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $admins = [
            [
                'name' => 'Admin 1',
                'email' => 'admin1@gmail.com',
                'password' => Hash::make('123'),
                'is_admin' => true
            ],
            [
                'name' => 'Admin 2',
                'email' => 'admin2@gmail.com',
                'password' => Hash::make('123'),
                'is_admin' => true
            ],
            [
                'name' => 'Admin 3',
                'email' => 'admin3@gmail.com',
                'password' => Hash::make('123'),
                'is_admin' => true
            ],
        ];

        foreach ($admins as $admin) {
            User::create($admin);
        }
    }
}