<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class EksemplarBuku extends Model
{
    use HasFactory;
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eksemplar_buku';
    protected $primaryKey = 'nomor_eksemplar';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['nomor_eksemplar','kode_buku','status_eksemplar','kondisi','tanggal_masuk'];
    public function buku() {
        return $this->belongsTo(Buku::class, 'kode_buku','kode_buku');
    }
    public function peminjaman() {
        return $this->hasMany(Peminjaman::class, 'nomor_eksemplar','nomor_eksemplar');
    }
}
