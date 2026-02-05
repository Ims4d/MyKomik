<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'password' => Hash::make('password'),
                'role' => 'admin',
                'display_name' => 'Administrator',
            ]
        );

        User::updateOrCreate(
            ['username' => 'user'],
            [
                'password' => Hash::make('password'),
                'role' => 'user',
                'display_name' => 'Regular User',
            ]
        );
    }
}