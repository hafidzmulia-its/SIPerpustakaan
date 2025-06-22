<?php

namespace App\Http\Controllers;

use App\Models\EksemplarBuku;
use App\Models\Buku;
use Illuminate\Http\Request;

class EksemplarBukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $sort_by = $request->get('sort_by', 'nomor_eksemplar');
        $sort_dir = $request->get('sort_dir', 'asc');
        $per_page = $request->get('per_page', 10);

        $allowed = ['nomor_eksemplar', 'judul', 'kondisi'];

        $eksemplars = EksemplarBuku::with('buku');

        if ($query) {
            $eksemplars->where('nomor_eksemplar', 'like', "%$query%")
                ->orWhereHas('buku', function($q) use ($query) {
                    $q->where('judul', 'like', "%$query%");
                })
                ->orWhere('status_eksemplar', 'like', "%$query%")
                ->orWhere('kondisi', 'like', "%$query%");
        }

        if (in_array($sort_by, $allowed)) {
            if ($sort_by === 'judul') {
                $eksemplars->join('bukus', 'eksemplar_buku.kode_buku', '=', 'bukus.kode_buku')
                    ->orderBy('bukus.judul', $sort_dir)
                    ->select('eksemplar_buku.*');
            } else {
                $eksemplars->orderBy($sort_by, $sort_dir);
            }
        }

        $eksemplars = $eksemplars->paginate($per_page)->appends([
            'query' => $query,
            'sort_by' => $sort_by,
            'sort_dir' => $sort_dir,
            'per_page' => $per_page,
        ]);

        // For create/edit modal: list of buku for dropdown
        $bukus = Buku::orderBy('judul')->get();

        return view('eksemplar.index', [
            'eksemplars' => $eksemplars,
            'query' => $query,
            'sort_by' => $sort_by,
            'sort_dir' => $sort_dir,
            'per_page' => $per_page,
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
        $validated = $request->validate([
            'nomor_eksemplar' => 'required|unique:eksemplar_buku,nomor_eksemplar',
            'kode_buku' => 'required|exists:bukus,kode_buku',
            'status_eksemplar' => 'required',
            'kondisi' => 'required',
        ]);
        EksemplarBuku::create($validated);
        return redirect()->route('eksemplar.index')->with('success', 'Eksemplar berhasil ditambahkan.');
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
    public function update(Request $request, EksemplarBuku $eksemplar)
    {
        $validated = $request->validate([
            // nomor_eksemplar should not be updated
            'kode_buku' => 'required|exists:bukus,kode_buku',
            'status_eksemplar' => 'required',
            'kondisi' => 'required',
        ]);
        $eksemplar->update($validated);
        return redirect()->route('eksemplar.index')->with('success', 'Eksemplar berhasil diupdate.');
    }

    public function destroy(EksemplarBuku $eksemplar)
    {
        // Cek jika ada peminjaman yang belum dikembalikan
            $adaDipinjam = $eksemplar->peminjaman()
            ->whereNull('tanggal_pengembalian')
            ->exists();

        if ($adaDipinjam) {
            return redirect()->route('eksemplar.index')
                ->with('error', 'Tidak dapat menghapus eksemplar karena masih ada peminjaman yang belum dikembalikan.');
        }
        $eksemplar->delete();
        return redirect()->route('eksemplar.index')->with('success', 'Eksemplar berhasil dihapus.');
    }
}
