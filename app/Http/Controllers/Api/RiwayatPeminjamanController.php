<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RiwayatPeminjamanResource; // Pastikan Anda sudah membuat resource ini
use App\Models\RiwayatPeminjaman; // Pastikan model ini ada
use App\Models\DaftarBarang; // Menambahkan model DaftarBarang untuk update status barang
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validbarangator;

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
        // Validbarangasi request data
        $request->validbarangate([
            'idbarang' => 'required|exists:daftarbarang,idbarang',
            'iduser' => 'required|exists:users,iduser',
            'tanggal_pinjam' => 'required|date',
            'status' => 'required|string',
        ]);

        // Membuat record baru untuk RiwayatPeminjaman
        $riwayat = new RiwayatPeminjaman();
        $riwayat->idbarang = $request->idbarang;
        $riwayat->iduser = $request->iduser;
        $riwayat->tanggal_pinjam = $request->tanggal_pinjam;
        $riwayat->status = $request->status;
        $riwayat->keterangan = $request->keterangan ?? ''; // Menambahkan default keterangan jika tidbarangak ada
        $riwayat->save();

        // Jika statusnya Diproses, update status barang menjadi Dipinjam
        if ($riwayat->status === 'Diproses') {
            $barang = DaftarBarang::find($riwayat->idbarang);
            if ($barang) {
                $barang->status = 'Dipinjam';
                $barang->save();
            }
        }

        return response()->json([
            'message' => 'Riwayat peminjaman tercatat',
            'data' => new RiwayatPeminjamanResource($riwayat)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RiwayatPeminjaman  $riwayatPeminjaman
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(RiwayatPeminjaman $riwayatPeminjaman)
    {
        // Hapus riwayat peminjaman
        $riwayatPeminjaman->delete();

        // Update status barang menjadi Tersedia setelah penghapusan riwayat
        $barang = DaftarBarang::find($riwayatPeminjaman->idbarang);
        if ($barang) {
            $barang->status = 'Tersedia';
            $barang->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Riwayat peminjaman deleted successfully.'
        ]);
    }
    // Setujui peminjaman
    public function setujui($idbarang)
    {
        $riwayat = RiwayatPeminjaman::findOrFail($idbarang);
        $riwayat->status = 'Disetujui';
        $riwayat->save();

        return response()->json(['message' => 'Peminjaman disetujui']);
    }

    // Tidbarangak setujui peminjaman
    public function tidbarangakSetujui($idbarang)
    {
        $riwayat = RiwayatPeminjaman::findOrFail($idbarang);
        $riwayat->status = 'Tidak Disetujui';
        $riwayat->save();

        return response()->json(['message' => 'Peminjaman tidak disetujui']);
    }

    // Setujui pengembalian barang
    public function setujuiPengembalian($idbarang)
    {
        $riwayat = RiwayatPeminjaman::findOrFail($idbarang);
        $riwayat->status = 'Disetujui';
        $riwayat->tanggal_kembali = now(); // Set tanggal kembali
        $riwayat->save();

        return response()->json(['message' => 'Pengembalian barang disetujui']);
    }

    // Tidbarangak setujui pengembalian barang
    public function tidbarangakSetujuiPengembalian($idbarang)
    {
        $riwayat = RiwayatPeminjaman::findOrFail($idbarang);
        $riwayat->status = 'Tidak Disetujui';
        $riwayat->save();

        return response()->json(['message' => 'Pengembalian barang tidak disetujui']);
    }
}
