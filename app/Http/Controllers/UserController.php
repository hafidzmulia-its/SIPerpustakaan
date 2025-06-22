<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $sort_by = $request->get('sort_by', 'username');
        $sort_dir = $request->get('sort_dir', 'asc');
        $per_page = $request->get('per_page', 10);

        $allowed = ['username', 'level_user', 'created_at'];

        $users = User::whereIn('level_user', ['petugas', 'admin']);

        if ($query) {
            $users->where(function($q) use ($query) {
                $q->where('username', 'like', "%$query%")
                ->orWhere('level_user', 'like', "%$query%");
            });
        }

        if (in_array($sort_by, $allowed)) {
            $users->orderBy($sort_by, $sort_dir);
        }

        $users = $users->paginate($per_page)->appends([
            'query' => $query,
            'sort_by' => $sort_by,
            'sort_dir' => $sort_dir,
            'per_page' => $per_page,
        ]);

        return view('users.index', [
            'users' => $users,
            'query' => $query,
            'sort_by' => $sort_by,
            'sort_dir' => $sort_dir,
            'per_page' => $per_page,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'level_user' => 'required|in:siswa,petugas,admin',
            'nama_siswa' => 'required_if:level_user,siswa',
            'kelas' => 'required_if:level_user,siswa',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'password' => bcrypt($validated['password']),
            'level_user' => $validated['level_user'],
        ]);

        if ($validated['level_user'] === 'siswa') {
            \App\Models\Siswa::create([
                'nis' => $validated['username'],
                'nama_siswa' => $validated['nama_siswa'],
                'kelas' => $validated['kelas'],
            ]);
        }

        return redirect()->route('users.index')->with('success', 'Akun berhasil dibuat.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'level_user' => 'required|in:siswa,petugas,admin',
            'nama_siswa' => 'required_if:level_user,siswa',
            'kelas' => 'required_if:level_user,siswa',
            'password' => 'nullable|min:6',
        ];
    
        // Only validate username if not siswa
        if ($user->level_user !== 'siswa' && $request->level_user !== 'siswa') {
            $rules['username'] = 'required|unique:users,username,' . $user->id;
        }
    
        $validated = $request->validate($rules);
    
        // Update username only if not siswa
        if ($user->level_user !== 'siswa' && $request->level_user !== 'siswa' && isset($validated['username'])) {
            $user->username = $validated['username'];
        }
    
        $user->level_user = $validated['level_user'];
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        $user->save();
    
        // If siswa, update or create Siswa record
        if ($validated['level_user'] === 'siswa') {
            $siswa = \App\Models\Siswa::firstOrNew(['nis' => $user->username]);
            $siswa->nama_siswa = $validated['nama_siswa'];
            $siswa->kelas = $validated['kelas'];
            $siswa->save();
        } else {
            // If not siswa, optionally delete Siswa record
            \App\Models\Siswa::where('nis', $user->username)->delete();
        }
    
        return redirect()->route('users.index')->with('success', 'Akun berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->level_user === 'siswa') {
            $siswa = \App\Models\Siswa::where('nis', $user->username)->first();
            if ($siswa && $siswa->peminjaman()->exists()) {
                return redirect()->route('users.index')
                    ->with('error', 'Tidak dapat menghapus akun siswa yang masih memiliki peminjaman.');
            }
            // Optionally, delete the siswa record too
            if ($siswa) $siswa->delete();
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Akun berhasil dihapus.');
    }
}
