<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Buku;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Faker\Generator as FakerGenerator;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();   
        for( $i = 1; $i <= 10; $i++) {
            Buku::create([
                'kode_buku' => 'BKU' . str_pad($i, 3, '0', STR_PAD_LEFT), // Generate kode_buku like BKU001, BKU002, etc.
                'judul' => $faker->sentence(3), // Example book title
                'pengarang' => $faker->name(), // Example author name
                'tahun_terbit' => 2000 + $i, // Example year of publication
            ]);
        }
        // Create a specific book with a known kode_buku
        Buku::create([
            'kode_buku' => 'BKU000',
            'judul' => 'Buku Contoh',
            'pengarang' => 'Pengarang Contoh',
            'tahun_terbit' => 2020,
            'cover' => 'img/no-cover.png', // Default cover image
        ]);
    
    }
}
