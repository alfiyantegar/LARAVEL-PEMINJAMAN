<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiwayatPeminjamanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->idriwpen, // ID riwayat peminjaman
            'id_user' => $this->iduser, // ID pengguna
            'nama_user' => $this->user->username, // Nama pengguna (dari relasi user)
            'id_barang' => $this->idbarang, // ID barang yang dipinjam
            'nama_barang' => $this->barang->name, // Nama barang (dari relasi barang)
            'tanggal_pinjam' => $this->tanggal_pinjam->toDateTimeString(), // Tanggal peminjaman
            'tanggal_kembali' => $this->tanggal_kembali ? $this->tanggal_kembali->toDateTimeString() : null, // Tanggal pengembalian, jika ada
            'status' => $this->status, // Status peminjaman (Disetujui, Tidak Disetujui, Sedang Diproses)
            'keterangan' => $this->keterangan, // Keterangan tambahan
        ];
    }
}

