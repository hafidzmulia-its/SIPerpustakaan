<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
class Peminjaman extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $fillable = ['nis','nomor_eksemplar','tanggal_peminjaman','tanggal_pengembalian','jumlah_denda'];
    protected $dates = ['tanggal_peminjaman','tanggal_pengembalian'];
    public function siswa() {
        return $this->belongsTo(Siswa::class, 'nis','nis');
    }
    public function eksemplar() {
        return $this->belongsTo(EksemplarBuku::class,'nomor_eksemplar','nomor_eksemplar');
    }
    public function getDendaAttribute()
    {
        // Use the same rules as your controller
        $batasHari = 7;
        $dendaPerHari = 1000;

        $start = $this->tanggal_peminjaman ? Carbon::parse($this->tanggal_peminjaman) : null;
        $end = $this->tanggal_pengembalian ? Carbon::parse($this->tanggal_pengembalian) : now();

        if (!$start) return 0;

        $days = (int) $start->diffInDays($end);
        $late = max(0, $days - $batasHari);
        return $late * $dendaPerHari;
    }
    public function getKeterlambatanAttribute()
    {
        $batasHari = 7;
        $start = $this->tanggal_peminjaman ? \Carbon\Carbon::parse($this->tanggal_peminjaman) : null;
        $end = $this->tanggal_pengembalian ? \Carbon\Carbon::parse($this->tanggal_pengembalian) : now();

        if (!$start) return 0;

        $days = (int) $start->diffInDays($end);
        return max(0, $days - $batasHari);
    }
}
