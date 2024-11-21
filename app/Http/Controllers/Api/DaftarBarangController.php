<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DaftarBarangResource;
use App\Models\DaftarBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DaftarBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = DaftarBarang::all();

        return response()->json([
            'success' => true,
            'data' => DaftarBarangResource::collection($barang),
            'message' => 'Daftar barang retrieved successfully.'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'merk' => 'required|string|max:100',
            'jenisbarang' => 'required|string|in:Printer,Scanner,PC Unit,Handphone',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string',
            'photo' => 'nullable', // Dapat berupa URL atau file
            'status' => 'required|string|in:Tersedia,Dipinjam',
            'jumlah_barang' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Proses photo (jika ada file upload)
        $data = $request->all();
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('photos', $filename, 'public');
            $data['photo'] = $path; // Simpan path ke database
        }

        // Simpan data
        $barang = DaftarBarang::create($data);

        return response()->json([
            'success' => true,
            'data' => new DaftarBarangResource($barang),
            'message' => 'Barang created successfully.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DaftarBarang $daftarBarang)
    {
        return response()->json([
            'success' => true,
            'data' => new DaftarBarangResource($daftarBarang),
            'message' => 'Barang retrieved successfully.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DaftarBarang $daftarBarang)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:100',
            'merk' => 'sometimes|required|string|max:100',
            'jenisbarang' => 'sometimes|required|string|in:Printer,Scanner,PC Unit,Handphone',
            'code' => 'sometimes|required|string|max:50',
            'description' => 'nullable|string',
            'photo' => 'nullable',
            'status' => 'sometimes|required|string|in:Tersedia,Dipinjam',
            'jumlah_barang' => 'sometimes|required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Proses photo jika diunggah
        $data = $request->all();
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('photos', $filename, 'public');
            $data['photo'] = $path;
        }

        $daftarBarang->update($data);

        return response()->json([
            'success' => true,
            'data' => new DaftarBarangResource($daftarBarang),
            'message' => 'Barang updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DaftarBarang $daftarBarang)
    {
        $daftarBarang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang deleted successfully.'
        ]);
    }
}
