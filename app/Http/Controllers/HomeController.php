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
    public function popular(Request $request)
    {
        $search = $request->search;
        $sort_by = $request->get('sort_by', 'kode_buku');
        $sort_dir = $request->get('sort_dir', 'desc');
        $allowed = ['kode_buku', 'judul', 'pengarang', 'tahun_terbit', 'peminjaman'];
    
        $bukus = \App\Models\Buku::with(['eksemplar.peminjaman'])
            ->get()
            ->each->append(['tersedia', 'total_peminjam']);
    
        // Sort by popularity (total peminjam)
        $bukus = $bukus->sortByDesc(fn($buku) => $buku->eksemplar->sum(fn($e) => $e->peminjaman->count()))->values();
    
        // Filter by search if needed
        if ($search) {
            $bukus = $bukus->filter(function ($buku) use ($search) {
                return str_contains(strtolower($buku->judul ?? ''), strtolower($search))
                    || str_contains(strtolower($buku->pengarang ?? ''), strtolower($search))
                    || str_contains(strtolower($buku->kode_buku ?? ''), strtolower($search))
                    || str_contains(strtolower($buku->tahun_terbit ?? ''), strtolower($search));
            })->values();
        }
    
        // Sort by selected column if needed
        if (in_array($sort_by, $allowed)) {
            if ($sort_by === 'peminjaman') {
                $bukus = $sort_dir === 'desc'
                    ? $bukus->sortByDesc(fn($buku) => $buku->eksemplar->sum(fn($e) => $e->peminjaman->count()))->values()
                    : $bukus->sortBy(fn($buku) => $buku->eksemplar->sum(fn($e) => $e->peminjaman->count()))->values();
            } else {
                $bukus = $sort_dir === 'desc'
                    ? $bukus->sortByDesc($sort_by)->values()
                    : $bukus->sortBy($sort_by)->values();
            }
        }
    
        // Paginate manually since it's a collection
        $perPage = $request->get('per_page', 20);
        $page = $request->get('page', 1);
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $bukus->forPage($page, $perPage),
            $bukus->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    
        return view('buku_all', [
            'title' => 'Semua Buku Populer',
            'bukus' => $paginated,
            'allowed' => $allowed,
            'search' => $search,
            'sort_by' => $sort_by,
            'sort_dir' => $sort_dir,
        ]);
    }
    
    public function tersedia(Request $request)
    {
        $search = $request->search;
        $sort_by = $request->get('sort_by', 'kode_buku');
        $sort_dir = $request->get('sort_dir', 'asc');
        $allowed = ['kode_buku', 'judul', 'pengarang', 'tahun_terbit'];
    
        $bukus = \App\Models\Buku::with(['eksemplar.peminjaman'])
            ->get()
            ->each->append('tersedia')
            ->filter(fn($buku) => $buku->tersedia > 0)
            ->values();
    
        // Filter by search if needed
        if ($search) {
            $bukus = $bukus->filter(function ($buku) use ($search) {
                return str_contains(strtolower($buku->judul), strtolower($search))
                    || str_contains(strtolower($buku->pengarang), strtolower($search))
                    || str_contains(strtolower($buku->kode_buku), strtolower($search))
                    || str_contains(strtolower($buku->tahun_terbit), strtolower($search));
            })->values();
        }
    
        // Sort by selected column if needed
        if (in_array($sort_by, $allowed)) {
            $bukus = $sort_dir === 'desc'
                ? $bukus->sortByDesc($sort_by)->values()
                : $bukus->sortBy($sort_by)->values();
        }
    
        // Paginate manually since it's a collection
        $perPage = $request->get('per_page', 20);
        $page = $request->get('page', 1);
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $bukus->forPage($page, $perPage),
            $bukus->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    
        return view('buku_all', [
            'title' => 'Semua Buku Tersedia',
            'bukus' => $paginated,
            'allowed' => $allowed,
            'search' => $search,
            'sort_by' => $sort_by,
            'sort_dir' => $sort_dir,
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
