<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DaftarBarangResource; // Pastikan Anda sudah membuat resource ini
use App\Models\DaftarBarang; // Pastikan model ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DaftarBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $barang = DaftarBarang::all(); // Mengambil semua barang
        return response()->json([
            'success' => true,
            'data' => DaftarBarangResource::collection($barang), // Menggunakan resource untuk format data
            'message' => 'Daftar barang retrieved successfully.'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'merk' => 'required|string|max:100',
            'jenisbarang' => 'required|string|in:Printer,Scanner,PC Unit,Handphone', // Sesuaikan dengan jenis barang
            'code' => 'required|string|max:50',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Sesuaikan ukuran foto
            'status' => 'required|string|in:Tersedia,Dipinjam', // Sesuaikan dengan status
            'jumlah_barang' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Menyimpan barang baru
        $barang = DaftarBarang::create($request->all());

        return response()->json([
            'success' => true,
            'data' => new DaftarBarangResource($barang), // Menggunakan resource untuk format data
            'message' => 'Barang created successfully.'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DaftarBarang  $daftarBarang
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(DaftarBarang $daftarBarang)
    {
        return response()->json([
            'success' => true,
            'data' => new DaftarBarangResource($daftarBarang), // Menggunakan resource untuk format data
            'message' => 'Barang retrieved successfully.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DaftarBarang  $daftarBarang
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, DaftarBarang $daftarBarang)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:100',
            'merk' => 'sometimes|required|string|max:100',
            'jenisbarang' => 'sometimes|required|string|in:Printer,Scanner,PC Unit,Handphone',
            'code' => 'sometimes|required|string|max:50',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|required|string|in:Tersedia,Dipinjam',
            'jumlah_barang' => 'sometimes|required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Memperbarui barang
        $daftarBarang->update($request->all());

        return response()->json([
            'success' => true,
            'data' => new DaftarBarangResource($daftarBarang), // Menggunakan resource untuk format data
            'message' => 'Barang updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DaftarBarang  $daftarBarang
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DaftarBarang $daftarBarang)
    {
        $daftarBarang->delete(); // Menghapus barang

        return response()->json([
            'success' => true,
            'message' => 'Barang deleted successfully.'
        ]);
    }
}
