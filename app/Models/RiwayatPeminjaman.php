<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'riwayatpeminjaman'; // Nama tabel di database

    protected $fillable = [
        'idbarang',
        'iduser',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Hubungan dengan model DaftarBarang
    public function daftarBarang()
    {
        return $this->belongsTo(DaftarBarang::class, 'idbarang', 'id');
    }

    // Hubungan dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }
}
