<?php

use App\Http\Controllers\ProfileController;
use App\Models\Buku;
use App\Models\EksemplarBuku;
use App\Models\Peminjaman;
use App\Models\Siswa;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\EksemplarBukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/buku/popular', [HomeController::class, 'popular'])->name('buku.popular');
Route::get('/buku/tersedia', [HomeController::class, 'tersedia'])->name('buku.tersedia');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/', [HomeController::class, 'index'])->name('home'); 
    Route::resource('buku', BukuController::class);
    Route::resource('eksemplar', EksemplarBukuController::class);
    Route::resource('peminjaman', PeminjamanController::class);
    Route::resource('pengembalian', PengembalianController::class);
    Route::resource('siswa', SiswaController::class);
    Route::resource('users', UserController::class);
    Route::get('/laporan', [PengembalianController::class, 'laporan'])->name('laporan');
    
});

require __DIR__.'/auth.php';
