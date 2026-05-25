<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;

class PetugasLaporanController extends Controller
{
    public function index()
    {
        $totalPeminjaman   = Peminjaman::count();
        $totalDikembalikan = Peminjaman::where('status', 'dikembalikan')->count();
        $totalTerlambat    = Peminjaman::where('status', 'terlambat')->count();
        $totalDenda        = Peminjaman::where('denda', '>', 0)->sum('denda');
        $totalBuku         = Book::count();
        $totalUser         = User::where('role', 'user')->count();
        $totalPetugas      = User::where('role', 'petugas')->count();

        $peminjamanBulanIni = Peminjaman::whereMonth('tanggal_pinjam', now()->month)
            ->whereYear('tanggal_pinjam', now()->year)
            ->count();

        $bukuPopuler = Book::withCount('peminjaman')
            ->orderBy('peminjaman_count', 'desc')
            ->take(5)
            ->get();

        // Deteksi nama kolom kategori secara otomatis
        $kategoriList = collect();
        try {
            $cols = \DB::getSchemaBuilder()->getColumnListing('categories');
            $sortCol = in_array('nama', $cols) ? 'nama'
                : (in_array('name', $cols) ? 'name'
                : (in_array('nama_kategori', $cols) ? 'nama_kategori' : null));
            $kategoriList = $sortCol
                ? Category::orderBy($sortCol)->get()
                : Category::all();
        } catch (\Exception $e) {
            $kategoriList = collect();
        }

        return view('petugas.laporan.index', compact(
            'totalPeminjaman', 'totalDikembalikan', 'totalTerlambat',
            'totalDenda', 'totalBuku', 'totalUser', 'totalPetugas',
            'peminjamanBulanIni', 'bukuPopuler', 'kategoriList'
        ));
    }

    // ── LAPORAN PEMINJAMAN ──────────────────────────────────────────────────
    public function cetakPeminjaman(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->get('date_to',   now()->format('Y-m-d'));
        $status   = $request->get('status');

        $query = Peminjaman::with(['user', 'book'])
            ->whereDate('tanggal_pinjam', '>=', $dateFrom)
            ->whereDate('tanggal_pinjam', '<=', $dateTo);

        if ($status) {
            $query->where('status', $status);
        }

        $peminjaman = $query->orderBy('tanggal_pinjam', 'desc')->get();

        $stats = [
            'total'        => $peminjaman->count(),
            'dipinjam'     => $peminjaman->where('status', 'dipinjam')->count(),
            'dikembalikan' => $peminjaman->where('status', 'dikembalikan')->count(),
            'terlambat'    => $peminjaman->where('status', 'terlambat')->count(),
            'total_denda'  => $peminjaman->sum('denda'),
            'denda_lunas'  => $peminjaman->where('bayar_denda', true)->sum('denda'),
            'denda_belum'  => $peminjaman->where('bayar_denda', false)->where('denda', '>', 0)->sum('denda'),
        ];

        $periodeLabel = Carbon::parse($dateFrom)->translatedFormat('d F Y')
            . ' – '
            . Carbon::parse($dateTo)->translatedFormat('d F Y');

        return view('petugas.laporan.cetak-peminjaman', compact(
            'peminjaman', 'stats', 'dateFrom', 'dateTo', 'status', 'periodeLabel'
        ));
    }

    // ── LAPORAN BUKU ────────────────────────────────────────────────────────
    public function cetakBuku(Request $request)
    {
        $kategoriId = $request->get('kategori');

        $buku = Book::with(['category', 'peminjaman'])
            ->when($kategoriId, fn($q) => $q->where('kategori_id', $kategoriId))
            ->withCount('peminjaman')
            ->orderBy('judul')
            ->get();

        $stats = [
            'total'          => $buku->count(),
            'total_stok'     => $buku->sum('stok'),
            'total_dipinjam' => $buku->sum('peminjaman_count'),
            'paling_populer' => $buku->sortByDesc('peminjaman_count')->first(),
        ];

        return view('petugas.laporan.cetak-buku', compact('buku', 'stats', 'kategoriId'));
    }

    // ── LAPORAN PENGGUNA ────────────────────────────────────────────────────
    public function cetakUser(Request $request)
    {
        $users = User::where('role', 'user')
            ->withCount('peminjaman')
            ->with(['peminjaman' => fn($q) => $q->where('status', 'dipinjam')])
            ->orderBy('name')
            ->get();

        $stats = [
            'total'          => $users->count(),
            'aktif_pinjam'   => $users->filter(fn($u) => $u->peminjaman->count() > 0)->count(),
            'total_pinjaman' => $users->sum('peminjaman_count'),
            'user_baru'      => User::where('role', 'user')
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];

        return view('petugas.laporan.cetak-user', compact('users', 'stats'));
    }
}