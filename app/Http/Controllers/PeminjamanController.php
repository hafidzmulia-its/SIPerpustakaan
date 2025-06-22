<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\EksemplarBuku;
use App\Models\Siswa;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Step 1: Cari siswa berdasarkan NIS
        $query_nis = $request->input('query_nis');
        $siswa = null;
        $active_peminjaman = collect();

        if ($query_nis) {
            $siswa = Siswa::where('nis', $query_nis)->first();
            if ($siswa) {
                $active_peminjaman = $siswa->peminjaman()
                    ->whereNull('tanggal_pengembalian')
                    ->get();
            }
        }

        // Step 2: Cari buku berdasarkan nomor eksemplar
        $query_eksemplar = $request->input('query_eksemplar');
        $found_eksemplar = null;
        if ($query_eksemplar) {
            $found_eksemplar = EksemplarBuku::with('buku')
                ->where('nomor_eksemplar', $query_eksemplar)
                ->first();
        }

        // Step 3: Keranjang peminjaman (pakai session)
        $keranjang = session('keranjang_peminjaman', []);

        // Tambah ke keranjang jika eksemplar ditemukan dan belum penuh

        // After finding $found_eksemplar ...
        if ($found_eksemplar && $siswa && count($keranjang) < 2) {
            // Cek apakah eksemplar sedang dipinjam
            $sedang_dipinjam = \App\Models\Peminjaman::where('nomor_eksemplar', $found_eksemplar->nomor_eksemplar)
                ->whereNull('tanggal_pengembalian')
                ->exists();

            if ($sedang_dipinjam) {
                return back()->with('error', 'Eksemplar ini sedang dipinjam dan belum dikembalikan.');
            }

            // Cek duplikat di keranjang
            if (!in_array($found_eksemplar->nomor_eksemplar, array_column($keranjang, 'nomor_eksemplar'))) {
                $keranjang[] = [
                    'nomor_eksemplar' => $found_eksemplar->nomor_eksemplar,
                    'judul_buku' => $found_eksemplar->buku->judul ?? '-',
                ];
                session(['keranjang_peminjaman' => $keranjang]);
            }
        }

        // Hapus dari keranjang
        if ($request->has('delete_eksemplar')) {
            $keranjang = array_filter($keranjang, function ($item) use ($request) {
                return $item['nomor_eksemplar'] != $request->input('delete_eksemplar');
            });
            session(['keranjang_peminjaman' => $keranjang]);
            return redirect()->route('peminjaman.index', ['query_nis' => $query_nis]);
        }

        return view('peminjaman.index', compact('siswa', 'active_peminjaman', 'keranjang', 'query_nis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // For NIS siswa
        if ($request->has('query_nis')) {
            $siswa = Siswa::where('nis', $request->query_nis)->first();
            if (!$siswa) {
                return redirect()->back()->withErrors(['Siswa dengan NIS tersebut tidak ditemukan.']);
            }
        }

        // For nomor eksemplar
        if ($request->has('query_eksemplar')) {
            $eksemplar = Eksemplar::where('nomor_eksemplar', $request->query_eksemplar)->first();
            if (!$eksemplar) {
                return redirect()->back()->withErrors(['Nomor eksemplar buku tidak ditemukan.']);
            }
        }
        $request->validate([
            'nis' => 'required|exists:siswas,nis',
            'nomor_eksemplar' => 'required|array|min:1|max:2',
            'nomor_eksemplar.*' => 'exists:eksemplar_buku,nomor_eksemplar',
        ]);
        
        // Cek apakah ada eksemplar yang sedang dipinjam
        foreach ($request->nomor_eksemplar as $no_eks) {
            $sedang_dipinjam = \App\Models\Peminjaman::where('nomor_eksemplar', $no_eks)
                ->whereNull('tanggal_pengembalian')
                ->exists();
            if ($sedang_dipinjam) {
                return back()->with('error', "Eksemplar $no_eks sedang dipinjam dan belum dikembalikan.");
            }
        }
    
        foreach ($request->nomor_eksemplar as $no_eks) {
            Peminjaman::create([
                'nis' => $request->nis,
                'nomor_eksemplar' => $no_eks,
                'tanggal_peminjaman' => now(),
                // 'tanggal_pengembalian' => null,
                'jumlah_denda' => 0,
            ]);
        }
    
        // Clear keranjang
        session()->forget('keranjang_peminjaman');
    
        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diproses!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        //
    }
}
