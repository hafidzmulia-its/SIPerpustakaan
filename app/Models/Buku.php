<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\EksemplarBuku;
use Illuminate\Database\Eloquent\SoftDeletes;
class Buku extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $primaryKey = 'kode_buku';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['kode_buku','judul','pengarang','tahun_terbit', 'cover'];
    public function eksemplar() {
        return $this->hasMany(EksemplarBuku::class, 'kode_buku','kode_buku');
    }
    public function scopeFilter($query, $search)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('pengarang', 'like', "%{$search}%");
            });
        }
        return $query;
    }
    public function scopeSort($query, $column = 'kode_buku', $direction = 'asc')
    {
        $allowed = ['kode_buku', 'judul', 'pengarang', 'tahun_terbit'];
        if (in_array($column, $allowed)) {
            return $query->orderBy($column, $direction);
        }
        return $query;
    }
    public function getTersediaAttribute()
    {
        // Total eksemplar for this book
        $total = $this->eksemplar()->count();

        // Count eksemplar that are currently lended (tanggal_pengembalian is null)
        $lended = EksemplarBuku::where('kode_buku', $this->kode_buku)
            ->whereHas('peminjaman', function($q) {
                $q->whereNull('tanggal_pengembalian');
            })->count();

        return $total - $lended;
    }
    public function getTotalPeminjamAttribute()
    {
        // Sum all peminjaman for all eksemplar of this buku
        return $this->eksemplar->sum(function($eksemplar) {
            return $eksemplar->peminjaman->count();
        });
    }
}
