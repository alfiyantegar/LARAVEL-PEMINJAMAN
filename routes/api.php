<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DaftarBarangController;
use App\Http\Controllers\Api\RiwayatPeminjamanController;
use App\Models\DaftarBarang;

Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Rute API untuk resource
Route::get('/daftarbarang/items', [DaftarBarangController::class, 'getItems']);
Route::apiResource('/daftarbarang', DaftarBarangController::class);
Route::get('riwayatpeminjaman', [RiwayatPeminjamanController::class, 'index']);
Route::post('riwayatpeminjaman', [RiwayatPeminjamanController::class, 'store']);
Route::delete('riwayatpeminjaman/{riwayatPeminjaman}', [RiwayatPeminjamanController::class, 'destroy']);
Route::post('/riwayatpeminjaman/setujui/{idriwpen}', [RiwayatPeminjamanController::class, 'setujui']); // Setujui peminjaman
Route::post('/riwayatpeminjaman/tidak-setujui/{idriwpen}', [RiwayatPeminjamanController::class, 'tidakSetujui']); // Tidak setujui peminjaman
Route::post('/riwayatpeminjaman/setujui-pengembalian/{idriwpen}', [RiwayatPeminjamanController::class, 'setujuiPengembalian']); // Setujui pengembalian
Route::post('/riwayatpeminjaman/tidak-setujui-peminjaman/{idriwpen}', [RiwayatPeminjamanController::class, 'tidakSetujuiPengembalian']); // Tidak setujui pengembalian
