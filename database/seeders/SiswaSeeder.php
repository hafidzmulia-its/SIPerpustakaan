<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('level_user', 'Siswa')->get();
        $faker = Faker::create();
        foreach ($users as $user) {
            Siswa::create([
                'nis' => $user->username, // Generate unique NIS
                'nama_siswa' => $faker->name(), // Generate a random name for the student
                'kelas' => $faker->randomElement(['X', 'XI', 'XII']) . $faker->randomElement(['-A', '-B', '-C']), // Randomly assign a class
            ]);
        }
    }
}
