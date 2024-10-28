<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('riwayatpeminjaman', function (Blueprint $table) {
        $table->id('idriwpen')->autoIncrement();
        $table->foreignId('id')->constrained('daftarbarang')->unique();
        $table->foreignId('iduser')->constrained('users')->unique();
        $table->timestamp('tanggal_pinjam')->default(now());
        $table->timestamp('tanggal_kembali')->nullable();
        $table->enum('status', ['Disetujui', 'Tidak Disetujui', 'Sedang Diproses']);
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayatpeminjaman');
    }
};
