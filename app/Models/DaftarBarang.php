<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarBarang extends Model
{
    use HasFactory;

    protected $table = 'daftarbarang'; // Nama tabel di database

    protected $fillable = [
        'name',
        'merk',
        'jenisbarang',
        'code',
        'description',
        'photo',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Hubungan dengan model RiwayatPeminjaman
    public function riwayatPeminjaman()
    {
        return $this->hasMany(RiwayatPeminjaman::class, 'idbarang', 'id');
    }
}
