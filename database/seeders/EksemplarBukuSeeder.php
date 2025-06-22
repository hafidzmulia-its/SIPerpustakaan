<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EksemplarBuku;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\Buku;

class EksemplarBukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bukus = Buku::all();
        $faker = Faker::create();
        foreach ($bukus as $buku) {
            for ($i = 1; $i <= 3; $i++) { // Create 5 copies for each book
                EksemplarBuku::create([
                    'nomor_eksemplar' => 'EK' . $faker->unique()->numerify('###'), // Generate unique kode_eksemplar
                    'kode_buku' => $buku->kode_buku, // Reference the buku's kode_buku
                    'status_eksemplar' => $faker->randomElement(['Asli', 'Fotokopian', 'Digital']),
                    'kondisi' => $faker->randomElement(['Baik', 'Rusak Ringan', 'Rusak Berat']),
                    'tanggal_masuk' => $faker->dateTimeBetween('-1 year', 'now'), // Random date within the last year
                ]);
            }
        }
    }
}
