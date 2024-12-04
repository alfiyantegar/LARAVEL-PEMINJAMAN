<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DaftarBarangResource;
use App\Models\DaftarBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            'jenisbarang' => 'required|string|in:Printer,Scanner,PC Unit,Handphone,Kendaraan',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validasi foto
            'status' => 'nullable|string|in:Tersedia,Dipinjam,Diproses', // Status boleh kosong
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Set default status 'Tersedia' jika tidak diberikan
        $status = $request->input('status', 'Tersedia');

        // Proses photo (jika ada file upload)
        $data = $request->all();
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            // Simpan file ke storage dan ambil path-nya
            $path = $file->storeAs('photos', $filename, 'public');
            $data['photo'] = $path; // Simpan path ke database
        }

        // Simpan data
        $data['status'] = $status; // Pastikan status sudah ter-set sebelum disimpan
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
            'jenisbarang' => 'sometimes|required|string|in:Printer,Scanner,PC Unit,Handphone,Kendaraan',
            'code' => 'sometimes|required|string|max:50',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validasi foto
            'status' => 'sometimes|required|string|in:Tersedia,Dipinjam,Diproses',
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
            // Hapus foto lama jika ada
            if ($daftarBarang->photo) {
                Storage::delete('public/' . $daftarBarang->photo);
            }

            $file = $request->file('photo');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            // Simpan file ke storage dan ambil path-nya
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

    public function pinjam($idbarang)
    {
        $barang = DaftarBarang::find($idbarang);
        if ($barang && $barang->status === 'Tersedia') {
            $barang->status = 'Dipinjam';
            $barang->save();
            return response()->json(['message' => 'Barang berhasil dipinjam']);
        }
        return response()->json(['message' => 'Barang tidak tersedia'], 400);
    }

    public function kembalikan($idbarang)
    {
        $barang = DaftarBarang::find($idbarang);
        if ($barang && $barang->status === 'Dipinjam') {
            $barang->status = 'Tersedia';
            $barang->save();
            return response()->json(['message' => 'Barang berhasil dikembalikan']);
        }
        return response()->json(['message' => 'Barang tidak ditemukan atau tidak dipinjam'], 400);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DaftarBarang $daftarBarang)
    {
        // Hapus foto barang jika ada
        if ($daftarBarang->photo) {
            Storage::delete('public/' . $daftarBarang->photo);
        }

        $daftarBarang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang deleted successfully.'
        ]);
    }
    public function getItems()
{
    $items = DaftarBarang::select('jenisbarang')
    ->groupBy('jenisbarang')
    ->get()
    ->map(function ($item) {
        return [
            'name' => $item->jenisbarang,
            'unit' => DaftarBarang::where('jenisbarang', $item->jenisbarang)->count(),  // Menghitung jumlah barang
            'borrowed' => DaftarBarang::where('jenisbarang', $item->jenisbarang)
                ->where('status', 'Dipinjam')
                ->count(),
        ];
    });


    return response()->json([
        'success' => true,
        'data' => $items,
        'message' => 'Items retrieved successfully.'
    ]);
}
}

