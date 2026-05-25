<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Peminjaman;

class PetugasDashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Book::count();
        $totalPeminjaman = Peminjaman::count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $peminjamanPending = Peminjaman::where('status', 'pending')->count();

        $peminjamanTerbaru = Peminjaman::with(['user', 'book'])
            ->whereIn('status', ['pending', 'dipinjam', 'terlambat'])
            ->latest()
            ->take(5)
            ->get();

        return view('petugas.dashboard', compact(
            'totalBuku', 'totalPeminjaman', 'peminjamanAktif',
            'peminjamanPending', 'peminjamanTerbaru'
        ));
    }
}