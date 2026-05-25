<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;

class PetugasNotifikasiController extends Controller
{
    public function index()
    {
        // Ambil aktivitas terbaru
        $pending = Peminjaman::with(['user', 'book'])
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        $dipinjam = Peminjaman::with(['user', 'book'])
            ->where('status', 'dipinjam')
            ->latest()
            ->take(10)
            ->get();

        $dikembalikan = Peminjaman::with(['user', 'book'])
            ->where('status', 'dikembalikan')
            ->latest()
            ->take(10)
            ->get();

        $ditolak = Peminjaman::with(['user', 'book'])
            ->where('status', 'ditolak')
            ->latest()
            ->take(5)
            ->get();

        // Denda yang belum dibayar
        $dendaBelum = Peminjaman::with(['user', 'book'])
            ->where('bayar_denda', false)
            ->whereRaw('denda > 0')
            ->latest()
            ->take(5)
            ->get();

        return view('petugas.notifikasi.index', compact(
            'pending', 'dipinjam', 'dikembalikan', 'ditolak', 'dendaBelum'
        ));
    }
}