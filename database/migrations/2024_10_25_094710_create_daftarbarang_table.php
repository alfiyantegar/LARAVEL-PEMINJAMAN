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
    Schema::create('daftarbarang', function (Blueprint $table) {
        $table->id('id')->autoIncrement();
        $table->string('name', 100);
        $table->string('merk', 100);
        $table->enum('jenisbarang', ['Printer', 'Scanner', 'PC Unit', 'Handphone']);
        $table->string('code', 50);
        $table->text('description')->nullable();
        $table->string('photo', 255)->nullable();
        $table->enum('status', ['Tersedia', 'Dipinjam', 'Diproses']);
        $table->integer('jumlah_barang');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftarbarang');
    }
};
