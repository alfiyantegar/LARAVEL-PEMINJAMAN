<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RiwayatPeminjamanResource; // Pastikan Anda sudah membuat resource ini
use App\Models\RiwayatPeminjaman; // Pastikan model ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RiwayatPeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $riwayat = RiwayatPeminjaman::all(); // Mengambil semua riwayat peminjaman
        return response()->json([
            'success' => true,
            'data' => RiwayatPeminjamanResource::collection($riwayat), // Menggunakan resource untuk format data
            'message' => 'Riwayat peminjaman retrieved successfully.'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'iduser' => 'required|integer|exists:login,iduser', // Pastikan iduser valid
            'id' => 'required|integer|exists:daftarbarang,id', // Pastikan id barang valid
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date',
            'status' => 'required|in:Disetujui,Tidak Disetujui,Sedang Diproses',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Menyimpan riwayat peminjaman baru
        $riwayat = RiwayatPeminjaman::create($request->all());

        return response()->json([
            'success' => true,
            'data' => new RiwayatPeminjamanResource($riwayat), // Menggunakan resource untuk format data
            'message' => 'Riwayat peminjaman created successfully.'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RiwayatPeminjaman  $riwayatPeminjaman
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(RiwayatPeminjaman $riwayatPeminjaman)
    {
        $riwayatPeminjaman->delete();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat peminjaman deleted successfully.'
        ]);
    }
}
