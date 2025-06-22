<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Models\Siswa;


class PengembalianController extends Controller
{
    const DENDA_PER_HARI = 1000;
    const BATAS_HARI_TANPA_DENDA = 7;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query_nis = $request->input('query_nis');
        $siswa = null;
        $active_peminjaman = collect();
        $total_denda = 0;
        $total_keterlambatan = 0;
    
        if ($query_nis) {
            $siswa = \App\Models\Siswa::where('nis', $query_nis)->first();
            if ($siswa) {
                $active_peminjaman = $siswa->peminjaman()
                    ->whereNull('tanggal_pengembalian')
                    ->with('eksemplar.buku')
                    ->get();
    
                // Use model accessors for calculation
                foreach ($active_peminjaman as $pinjam) {
                    $total_denda += $pinjam->denda;
                    $total_keterlambatan += $pinjam->keterlambatan;
                }
            }
        }
    
        return view('pengembalian.index', compact('siswa', 'active_peminjaman', 'query_nis', 'total_denda', 'total_keterlambatan'));
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
        $request->validate([
            'pengembalian' => 'required|array|min:1',
            'pengembalian.*' => 'exists:peminjamen,id',
        ]);
    
        foreach ($request->pengembalian as $id) {
            $peminjaman = \App\Models\Peminjaman::find($id);
            if ($peminjaman && $peminjaman->tanggal_pengembalian === null) {
                $peminjaman->tanggal_pengembalian = now();
                $peminjaman->jumlah_denda = $peminjaman->denda; // Use accessor for current calculation
                $peminjaman->save();
            }
        }
    
        return redirect()->route('pengembalian.index', ['query_nis' => $request->input('query_nis')])->with('success', 'Pengembalian berhasil diproses!');
    }
    public function laporan(Request $request)
    {
        $filter = $request->input('filter', 'hari'); // hari, bulan, tahun

        // Set date range
        if ($filter === 'bulan') {
            $start = now()->startOfMonth();
            $end = now()->endOfMonth();
            
        } elseif ($filter === 'tahun') {
            $start = now()->startOfYear();
            $end = now()->endOfYear();
        } else { // default: hari
            $start = now()->startOfDay();
            $end = now()->endOfDay();
        }
       

        // Total peminjaman (unit buku)
        $total_peminjaman = \App\Models\Peminjaman::whereBetween('tanggal_peminjaman', [$start, $end])->count();

        // Total denda
        $total_denda = \App\Models\Peminjaman::whereBetween('tanggal_pengembalian', [$start, $end])->sum('jumlah_denda');

        // Total anggota aktif (siswa yang pernah meminjam dalam periode)
        $anggota_aktif = \App\Models\Peminjaman::whereBetween('tanggal_peminjaman', [$start, $end])->distinct('nis')->count('nis');

        // Top 5 buku paling sering dipinjam
        $top_buku = \App\Models\Peminjaman::whereBetween('tanggal_peminjaman', [$start, $end])
    ->join('eksemplar_buku', 'peminjamen.nomor_eksemplar', '=', 'eksemplar_buku.nomor_eksemplar')
    ->join('bukus', 'eksemplar_buku.kode_buku', '=', 'bukus.kode_buku')
    ->select('bukus.kode_buku', 'bukus.judul', 'bukus.pengarang', 'bukus.cover')
    ->selectRaw('COUNT(peminjamen.id) as total')
    ->groupBy('bukus.kode_buku', 'bukus.judul', 'bukus.pengarang', 'bukus.cover')
    ->orderByDesc('total')
    ->take(5)
    ->get();

        return view('pengembalian.laporan', compact(
            'filter', 'total_peminjaman', 'total_denda', 'anggota_aktif', 'top_buku'
        ));
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
