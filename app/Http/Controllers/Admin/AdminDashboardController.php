<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Review;
use App\Models\Notification;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $admin = auth()->user();

        $stats = [
            'total_buku'      => Book::count(),
            'total_user'      => User::where('role', 'user')->count(),
            'peminjaman_aktif'=> Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count(),
            'pengembalian'    => Peminjaman::where('status', 'dikembalikan')->count(),
            'total_denda'     => Peminjaman::sum('denda'),
            'petugas_aktif'   => User::where('role', 'petugas')->count(),
            'menunggu'        => Peminjaman::where('status', 'pending')->count(),
            'total_ulasan'    => Review::count(),
        ];

        // Grafik 7 hari terakhir
        $grafik = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $grafik[] = [
                'label'       => $date->format('d M'),
                'peminjaman'  => Peminjaman::whereDate('created_at', $date)->count(),
                'pengembalian'=> Peminjaman::whereDate('tanggal_dikembalikan', $date)->count(),
            ];
        }

        // Ganti bagian grafik dari 6 hari menjadi 29 hari
    for ($i = 29; $i >= 0; $i--) {
    $date = Carbon::now()->subDays($i);
    $grafik[] = [
        'label'       => $date->format('d M'),
        'peminjaman'  => Peminjaman::whereDate('created_at', $date)->count(),
        'pengembalian'=> Peminjaman::whereDate('tanggal_dikembalikan', $date)->count(),
    ];
}

        // Peminjaman berdasarkan kategori
        $byKategori = Category::withCount(['books as total_pinjam' => function($q) {
            $q->join('peminjaman', 'books.id', '=', 'peminjaman.book_id');
        }])->orderByDesc('total_pinjam')->take(5)->get();

        // Buku terpopuler
        $bukuPopuler = Book::with('category')
            ->withCount('peminjaman')
            ->orderByDesc('peminjaman_count')
            ->take(5)->get();

        // Pengguna baru
        $userBaru = User::where('role', 'user')
            ->latest()->take(5)->get();

        // Aktivitas terbaru
        $aktivitas = Peminjaman::with(['user', 'book'])
            ->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'admin', 'stats', 'grafik',
            'byKategori', 'bukuPopuler', 'userBaru', 'aktivitas'
        ));
    }
}