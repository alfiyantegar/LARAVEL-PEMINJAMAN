<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DaftarBarangController;
use App\Http\Controllers\Api\RiwayatPeminjamanController;

// API routes are automatically assigned the "api" middleware group

// Rute login (gunakan hanya POST) dan beri nama 'login'
Route::post('/login', [UserController::class, 'login'])->name('login');

// Grup rute yang memerlukan autentikasi
Route::middleware('auth:sanctum')->group(function () {
    // Rute logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // API resources yang memerlukan autentikasi
    Route::apiResource('/user', UserController::class);
    Route::apiResource('/daftarbarang', DaftarBarangController::class);
    Route::apiResource('/riwayatpeminjaman', RiwayatPeminjamanController::class);
});
