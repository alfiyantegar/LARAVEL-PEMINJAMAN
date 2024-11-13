<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DaftarBarangController;
use App\Http\Controllers\Api\RiwayatPeminjamanController;

// Rute login (gunakan hanya POST)
Route::post('/login', [UserController::class, 'login']);

// Grup rute yang memerlukan autentikasi
Route::middleware('auth:sanctum')->group(function () {
    // Rute logout
    Route::post('/logout', [UserController::class, 'logout']);

    // API resources yang memerlukan autentikasi
    Route::apiResource('/user', UserController::class);
    Route::apiResource('/daftarbarang', DaftarBarangController::class);
    Route::apiResource('/riwayatpeminjaman', RiwayatPeminjamanController::class);
});

