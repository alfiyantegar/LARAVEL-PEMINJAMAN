<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DaftarBarangResource extends JsonResource
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
            'id' => $this->idbarang, // ID barang
            'name' => $this->name, // Nama barang
            'merk' => $this->merk, // Merek barang
            'jenis_barang' => $this->jenisbarang, // Jenis barang
            'code' => $this->code, // Kode barang
            'description' => $this->description, // Deskripsi barang
            'photo' => $this->photo, // URL foto barang
            'status' => $this->status, // Status barang (Tersedia, Terpinjam, dll.)
            'jumlah_barang' => $this->jumlah_barang, // Jumlah barang
            'created_at' => $this->created_at->toDateTimeString(), // Tanggal pembuatan
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null, // Tanggal pembaruan, jika ada
        ];
    }
}
