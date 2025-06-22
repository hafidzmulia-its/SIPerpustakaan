<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 users with unique usernames and emails
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'username' => 'user' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password'), // Use a common password for simplicity
                'remember_token' => Str::random(10),
            ]);
        }
        
        // Create an admin user
        User::create([
            'username' => 'admin',
            'email' => 'admin' . '@example.com',
            'password' => Hash::make('password'), // Use a specific password for admin
            'remember_token' => Str::random(10),
            'level_user' => 'Admin', // Set the level_user to Admin
        ]);
        // Create a petugas user
        User::create([
            'username' => 'petugas',
            'email' => 'petugas' . '@example.com',
            'password' => Hash::make('password'), // Use a specific password for petugas
            'remember_token' => Str::random(10),
            'level_user' => 'Petugas', // Set the level_user to Petugas
        ]);
    }
}
