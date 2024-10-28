<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->iduser, // Ganti dengan atribut ID pengguna
            'username' => $this->username, // Nama pengguna
            'password' => $this->password, // Nama pengguna
            'role' => $this->role, // Peran pengguna (misalnya admin, user)
            'created_at' => $this->created_at->toDateTimeString(), // Tanggal pembuatan
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null, // Tanggal pembaruan, jika ada
        ];
    }
}

