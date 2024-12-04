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
Route::apiResource('/user', UserController::class);
Route::get('/daftarbarang/items', [DaftarBarangController::class, 'getItems']);
Route::apiResource('/daftarbarang', DaftarBarangController::class);
Route::apiResource('/riwayatpeminjaman', RiwayatPeminjamanController::class);
