<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\BukuSeeder;
use Database\Seeders\SiswaSeeder;
use Database\Seeders\PeminjamanSeeder;
use Database\Seeders\EksemplarBukuSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            UserSeeder::class,
            BukuSeeder::class,
            SiswaSeeder::class,
            EksemplarBukuSeeder::class,
            PeminjamanSeeder::class,
        ]);
    }
}
