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
        $books = [
            [
                'kode_buku' => 'BKU000',
                'judul' => 'Bumi Manusia',
                'cover' => 'img/bumi_manusia.jpg',
                'pengarang' => 'Pramoedya Ananta Toer',
                'tahun_terbit' => 1980,
            ],
            [
                'kode_buku' => 'BKU001',
                'judul' => 'Laskar Pelangi',
                'cover' => 'img/laskar_pelangi.jpg',
                'pengarang' => 'Andrea Hirata',
                'tahun_terbit' => 2005,
            ],
            [
                'kode_buku' => 'BKU002',
                'judul' => 'Laut Bercerita',
                'cover' => 'img/laut_bercerita.jpg',
                'pengarang' => 'Leila S. Chudori',
                'tahun_terbit' => 2017,
            ],
            [
                'kode_buku' => 'BKU003',
                'judul' => 'Bumi',
                'cover' => 'img/buimi.jpg',
                'pengarang' => 'Tere Liye',
                'tahun_terbit' => 2014,
            ],
            [
                'kode_buku' => 'BKU004',
                'judul' => 'Bumi',
                'cover' => 'img/bumi.jpg',
                'pengarang' => 'Tere Liye',
                'tahun_terbit' => 2014,
            ],
            [
                'kode_buku' => 'BKU005',
                'judul' => 'Laut Bercerita',
                'cover' => 'img/laut_bercerita.jpg',
                'pengarang' => 'Leila S. Chudori',
                'tahun_terbit' => 2017,
            ],
            [
                'kode_buku' => 'BKU006',
                'judul' => 'Matahari Minor',
                'cover' => 'img/matahari_minor.jpg',
                'pengarang' => 'Tere Liye',
                'tahun_terbit' => 2015,
            ],
            [
                'kode_buku' => 'BKU007',
                'judul' => 'Perahu Kertas',
                'cover' => 'img/perahu_kertas.jpg',
                'pengarang' => 'Dee Lestari',
                'tahun_terbit' => 2009,
            ],
            [
                'kode_buku' => 'BKU008',
                'judul' => 'Negeri 5 Menara',
                'cover' => 'img/negeri_5_menara.jpg',
                'pengarang' => 'Ahmad Fuadi',
                'tahun_terbit' => 2009,
            ],
            [
                'kode_buku' => 'BKU009',
                'judul' => 'Bulan',
                'cover' => 'img/bulan.jpg',
                'pengarang' => 'Tere Liye',
                'tahun_terbit' => 2015,
            ],
            [
                'kode_buku' => 'BKU010',
                'judul' => 'Bulan',
                'cover' => 'img/bulan.jpg',
                'pengarang' => 'Tere Liye',
                'tahun_terbit' => 2015,
            ],
        ];

        foreach ($books as $book) {
            Buku::create($book);
        }
    }
}
