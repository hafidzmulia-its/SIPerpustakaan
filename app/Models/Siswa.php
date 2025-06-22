<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Siswa extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $fillable = ['nis','nama_siswa','kelas'];
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function peminjaman() {
        return $this->hasMany(Peminjaman::class, 'nis', 'nis');
    }
    public function getRouteKeyName()
    {
        return 'nis';
    }
}
