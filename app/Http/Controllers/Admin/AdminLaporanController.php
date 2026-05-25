<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Category;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminLaporanController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->user();

        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        // Ringkasan stats
        $stats = [
            'total_peminjaman'   => Peminjaman::whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->count(),
            'total_pengembalian' => Peminjaman::where('status', 'dikembalikan')->whereMonth('updated_at', $bulan)->whereYear('updated_at', $tahun)->count(),
            'total_terlambat'    => Peminjaman::where('status', 'terlambat')->count(),
            'total_denda'        => Peminjaman::whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->sum('denda'),
            'total_buku'         => Book::count(),
            'total_user'         => User::where('role', 'user')->count(),
        ];

        // Peminjaman per bulan (12 bulan terakhir)
        $grafikBulanan = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $grafikBulanan[] = [
                'label'       => $date->format('M Y'),
                'peminjaman'  => Peminjaman::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count(),
                'pengembalian'=> Peminjaman::where('status','dikembalikan')->whereMonth('updated_at', $date->month)->whereYear('updated_at', $date->year)->count(),
            ];
        }

        // Buku terpopuler
        $bukuPopuler = Book::with('category')
            ->withCount('peminjaman')
            ->orderByDesc('peminjaman_count')
            ->take(10)->get();

        // User teraktif
        $userAktif = User::where('role', 'user')
            ->withCount('peminjaman')
            ->orderByDesc('peminjaman_count')
            ->take(10)->get();

        // Peminjaman terbaru
        $peminjamanTerbaru = Peminjaman::with(['user','book.category'])
            ->latest()->take(15)->get();

        // Peminjaman terlambat
        $peminjamanTerlambat = Peminjaman::with(['user','book'])
            ->where('status','terlambat')
            ->latest()->get();

        // Kategori terpopuler
        $kategoriPopuler = Category::withCount(['books as total_pinjam' => function($q) {
            $q->join('peminjaman','books.id','=','peminjaman.book_id');
        }])->orderByDesc('total_pinjam')->get();

        return view('admin.laporan.index', compact(
            'admin','stats','grafikBulanan','bukuPopuler',
            'userAktif','peminjamanTerbaru','peminjamanTerlambat',
            'kategoriPopuler','bulan','tahun'
        ));
    }

    public function cetakPeminjaman(Request $request)
    {
        $admin = auth()->user();
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $peminjaman = Peminjaman::with(['user','book.category'])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->latest()->get();

        $stats = [
            'total'       => $peminjaman->count(),
            'dipinjam'    => $peminjaman->where('status','dipinjam')->count(),
            'dikembalikan'=> $peminjaman->where('status','dikembalikan')->count(),
            'terlambat'   => $peminjaman->where('status','terlambat')->count(),
            'total_denda' => $peminjaman->sum('denda'),
        ];

        $namaBulan = Carbon::createFromDate(null, $bulan, 1)->translatedFormat('F');

        return view('admin.laporan.cetak-peminjaman', compact(
            'admin','peminjaman','stats','bulan','tahun','namaBulan'
        ));
    }

    public function cetakBuku(Request $request)
    {
        $admin = auth()->user();
        $books = Book::with('category')->withCount('peminjaman')->orderByDesc('peminjaman_count')->get();

        return view('admin.laporan.cetak-buku', compact('admin','books'));
    }

    public function cetakUser(Request $request)
    {
        $admin = auth()->user();
        $users = User::where('role','user')->withCount('peminjaman')->orderByDesc('peminjaman_count')->get();

        return view('admin.laporan.cetak-user', compact('admin','users'));
    }

    public function cetakPetugas(Request $request)
    {
        $admin = auth()->user();
        $petugas = User::where('role','petugas')->get();

        return view('admin.laporan.cetak-petugas', compact('admin','petugas'));
    }
}