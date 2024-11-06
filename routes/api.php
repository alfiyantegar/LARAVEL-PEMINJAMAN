<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DaftarBarangController;
use App\Http\Controllers\Api\RiwayatPeminjamanController;

// Rute login
Route::match(['get', 'post'], '/login', [UserController::class, 'login']);

// Rute logout
Route::post('/logout', [UserController::class, 'logout']);

// API resources
Route::apiResource('/user', UserController::class);
Route::apiResource('/daftarbarang', DaftarBarangController::class);
Route::apiResource('/riwayatpeminjaman', RiwayatPeminjamanController::class);

