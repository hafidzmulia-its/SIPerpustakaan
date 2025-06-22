<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;


class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
            $search = $request->search;
        $sort_by = $request->get('sort_by', 'kode_buku');
        $sort_dir = $request->get('sort_dir', 'asc');

        $allowed = ['kode_buku', 'judul', 'pengarang', 'tahun_terbit'];

        if ($search) {
            $filteredBukus = Buku::filter($search)
                ->sort($sort_by, $sort_dir)
                ->get();
            return view('home', [
                'search' => $search,
                'filteredBukus' => $filteredBukus,
                'allowed' => $allowed,
                'sort_by' => $sort_by,
                'sort_dir' => $sort_dir,
            ]);
        } else {
            $popularBukus = Buku::with(['eksemplar.peminjaman'])
            ->get()->each->append(['tersedia', 'total_peminjam'])
            ->sortByDesc(function($buku) {
                return $buku->eksemplar->sum(fn($e) => $e->peminjaman->count());
            })
            ->values();

            $tersediaBukus = Buku::sort($sort_by, $sort_dir)
                ->get()->each->append('tersedia')
                ->filter(fn($buku) => $buku->tersedia > 0)
                ->values();

            $popularChunks = $popularBukus->chunk(5)->map(fn($chunk) => $chunk->toArray());
            $availableChunks = $tersediaBukus->chunk(5)->map(fn($chunk) => $chunk->toArray());

            return view('home', [
                'popularBukus' => $popularBukus,
                'tersediaBukus' => $tersediaBukus,
                'search' => null,
                'allowed' => $allowed,
                'sort_by' => $sort_by,
                'sort_dir' => $sort_dir,
                'popularChunks' => $popularChunks,
                'availableChunks' => $availableChunks,
            ]);
        }
    }
    public function popular()
    {
        $bukus = Buku::with(['eksemplar.peminjaman'])
            ->get()
            ->each->append(['tersedia', 'total_peminjam'])
            ->sortByDesc(fn($buku) => $buku->eksemplar->sum(fn($e) => $e->peminjaman->count()))
            ->values();
    
        return view('buku_all', [
            'title' => 'Semua Buku Populer',
            'bukus' => $bukus,
        ]);
    }

    public function tersedia()
    {
        $bukus = Buku::with(['eksemplar.peminjaman'])
            ->get()
            ->each->append('tersedia')
            ->filter(fn($buku) => $buku->tersedia > 0)
            ->values();

        return view('buku_all', [
            'title' => 'Semua Buku Tersedia',
            'bukus' => $bukus,
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EksemplarBuku $eksemplarBuku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EksemplarBuku $eksemplarBuku)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EksemplarBuku $eksemplarBuku)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EksemplarBuku $eksemplarBuku)
    {
        //
    }
}
