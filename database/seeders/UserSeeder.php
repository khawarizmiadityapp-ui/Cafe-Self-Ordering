<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@cafe.com'],
            [
                'name' => 'Admin Cafe',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@cafe.com'],
            [
                'name' => 'Kasir Utama',
                'password' => Hash::make('password'),
                'role' => 'kasir',
            ]
        );

        User::updateOrCreate(
            ['email' => 'dapur@cafe.com'],
            [
                'name' => 'Barista & Dapur',
                'password' => Hash::make('password'),
                'role' => 'dapur',
            ]
        );
    }
}
