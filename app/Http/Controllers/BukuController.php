<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $sort_by = $request->get('sort_by', 'kode_buku');
        $sort_dir = $request->get('sort_dir', 'asc');
        $per_page = $request->get('per_page', 10);

        $allowed = ['kode_buku', 'judul', 'pengarang', 'tahun_terbit'];

        $bukus = Buku::query();

        if ($query) {
            $bukus->where('kode_buku', 'like', "%$query%")
                ->orWhere('judul', 'like', "%$query%")
                ->orWhere('pengarang', 'like', "%$query%")
                ->orWhere('tahun_terbit', 'like', "%$query%");
        }

        if (in_array($sort_by, $allowed)) {
            $bukus->orderBy($sort_by, $sort_dir);
        }

        $bukus = $bukus->paginate($per_page)->appends([
            'query' => $query,
            'sort_by' => $sort_by,
            'sort_dir' => $sort_dir,
            'per_page' => $per_page,
        ]);

        return view('buku.index', [
            'bukus' => $bukus,
            'query' => $query,
            'sort_by' => $sort_by,
            'sort_dir' => $sort_dir,
            'per_page' => $per_page,
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
            'kode_buku' => 'required|unique:bukus,kode_buku',
            'judul' => 'required',
            'pengarang' => 'required',
            'tahun_terbit' => 'required|digits:4',
            'cover' => 'nullable|image|max:2048',
        ]);
    
        if ($request->hasFile('cover')) {
            // Store in storage/app/public/img
            $validated['cover'] = $request->file('cover')->store('img', 'public');
        }
    
        Buku::create($validated);
        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Buku $buku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buku $buku)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'judul' => 'required',
            'pengarang' => 'required',
            'tahun_terbit' => 'required|digits:4',
            'cover' => 'nullable|image|max:2048',
        ]);
    
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($buku->cover && \Storage::disk('public')->exists($buku->cover)) {
                \Storage::disk('public')->delete($buku->cover);
            }
            // Store new cover
            $validated['cover'] = $request->file('cover')->store('img', 'public');
        }
    
        $buku->update($validated);
        return redirect()->route('buku.index')->with('success', 'Buku berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Buku $buku)
    {

        if ($buku->eksemplar()->exists()) {
            return redirect()->route('buku.index')->with('error','Tidak dapat menghapus Buku yang masih memiliki Eksemplar. Hapus Eksemplar terlebih dahulu atau nonaktifkan.');
        }
        
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
