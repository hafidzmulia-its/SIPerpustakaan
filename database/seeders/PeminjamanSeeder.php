<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use App\Models\Siswa;
use App\Models\EksemplarBuku;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $siswaIds = Siswa::pluck('nis')->toArray(); // Get all siswa IDs
        $eksemplarIds = EksemplarBuku::pluck('nomor_eksemplar')->toArray(); // Get all eksemplar IDs

       for ($i = 0; $i < 20; $i++) {
        $pinjam = $faker->dateTimeBetween('-1 month', 'now');
        // 30% chance the book is not yet returned
        $isReturned = $faker->boolean(70);
        $kembali = $isReturned ? $faker->dateTimeBetween($pinjam, '+1 month') : null;

        // Calculate denda only if returned
        $jumlah_denda = null;
        if ($kembali) {
            $days = (int) (new \Carbon\Carbon($pinjam))->diffInDays(new \Carbon\Carbon($kembali));
            $late = max(0, $days - 7);
            $jumlah_denda = $late * 1000;
        }

        Peminjaman::create([
            'nis' => $faker->randomElement($siswaIds),
            'nomor_eksemplar' => $faker->randomElement($eksemplarIds),
            'tanggal_peminjaman' => $pinjam,
            'tanggal_pengembalian' => $kembali,
            'jumlah_denda' => $jumlah_denda,
        ]);
        }
    }
}
