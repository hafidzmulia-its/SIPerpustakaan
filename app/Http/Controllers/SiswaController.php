<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $sort_by = $request->get('sort_by', 'nis');
        $sort_dir = $request->get('sort_dir', 'asc');
        $per_page = $request->get('per_page', 5); // Default 10
    
        $allowed = ['nis', 'nama_siswa', 'kelas'];
    
        $siswas = Siswa::query();
    
        if ($query) {
            $siswas->where('nis', 'like', "%$query%")
                ->orWhere('nama_siswa', 'like', "%$query%")
                ->orWhere('kelas', 'like', "%$query%");
        }
    
        if (in_array($sort_by, $allowed)) {
            $siswas->orderBy($sort_by, $sort_dir);
        }
    
        $siswas = $siswas->paginate($per_page)->appends([
            'query' => $query,
            'sort_by' => $sort_by,
            'sort_dir' => $sort_dir,
            'per_page' => $per_page,
        ]);
    
        $usedNis = \App\Models\Siswa::pluck('nis')->toArray();
        $eligibleUsers = User::where('level_user', 'siswa')
            ->whereNotIn('username', $usedNis)
            ->get();
    
        return view('siswa.index', [
            'siswas' => $siswas,
            'query' => $query,
            'sort_by' => $sort_by,
            'sort_dir' => $sort_dir,
            'per_page' => $per_page,
            'eligibleUsers' => $eligibleUsers,
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
        'nis' => 'required|unique:siswas,nis|unique:users,username',
        'nama_siswa' => 'required',
        'kelas' => 'required',
        'password' => 'required|min:6',
    ]);

    // Create User first
    $user = \App\Models\User::create([
        'username' => $validated['nis'],
        'password' => bcrypt($validated['password']),
        'level_user' => 'siswa',
    ]);

    // Then create Siswa
    \App\Models\Siswa::create([
        'nis' => $validated['nis'],
        'nama_siswa' => $validated['nama_siswa'],
        'kelas' => $validated['kelas'],
    ]);

    return redirect()->route('siswa.index')->with('success', 'Siswa dan akun berhasil ditambahkan.');
}

    /**
     * Display the specified resource.
     */

     public function show(Request $request, Siswa $siswa)
     {
         $query = $request->input('query');
         $sort_by = $request->get('sort_by', 'tanggal_peminjaman');
         $sort_dir = $request->get('sort_dir', 'asc');
     
         $allowed = [
             'tanggal_peminjaman',
             'tanggal_pengembalian',
             'jumlah_denda',
             'judul_buku',
             'nomor_eksemplar'
         ];
     
         // Start the query on the peminjaman relationship
         $peminjamanQuery = $siswa->peminjaman()->with(['eksemplar.buku']);
     
         // Filtering
         if ($query) {
            $peminjamanQuery->where(function ($q) use ($query) {
                $q->where('id', 'like', "%$query%")
                  // nomor_eksemplar is on eksemplar
                  ->orWhereHas('eksemplar', function ($q2) use ($query) {
                      $q2->where('nomor_eksemplar', 'like', "%$query%")
                         // judul is on buku
                         ->orWhereHas('buku', function ($q3) use ($query) {
                             $q3->where('judul', 'like', "%$query%");
                         });
                  });
            });
        }
     
         // Sorting
         if ($sort_by === 'judul_buku') {
             $peminjamanQuery->join('eksemplars', 'peminjaman.eksemplar_id', '=', 'eksemplars.id')
                 ->join('bukus', 'eksemplars.buku_id', '=', 'bukus.id')
                 ->orderBy('bukus.judul', $sort_dir)
                 ->select('peminjaman.*');
         } elseif (in_array($sort_by, $allowed)) {
             $peminjamanQuery->orderBy($sort_by, $sort_dir);
         }
     
         $peminjaman = $peminjamanQuery->get();
     
         return view('siswa.show', [
             'siswa' => $siswa,
             'peminjaman' => $peminjaman,
             'query' => $query,
             'allowed' => $allowed,
             'sort_by' => $sort_by,
             'sort_dir' => $sort_dir,
         ]);
     }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nis' => 'required|unique:siswas,nis,' . $siswa->id,
            'nama_siswa' => 'required',
            'kelas' => 'required',
        ]);
        $siswa->update($validated);
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }
}
