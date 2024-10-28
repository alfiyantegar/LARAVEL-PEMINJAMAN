<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/login', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/user', App\Http\Controllers\Api\UserController::class);
Route::apiResource('/daftarbarang', App\Http\Controllers\Api\DaftarBarangController::class);
Route::apiResource('/riwayatpeminjaman', App\Http\Controllers\Api\RiwayatPeminjamanController::class);
