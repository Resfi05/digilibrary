<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Notification;
use Carbon\Carbon;

class AdminPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->user();

        $query = Peminjaman::with(['user', 'book.category']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', '%'.$request->search.'%'))
                  ->orWhereHas('book', fn($b) => $b->where('judul', 'like', '%'.$request->search.'%'));
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $peminjaman = $query->latest()->paginate(15)->appends($request->query());

        $stats = [
            'total'        => Peminjaman::count(),
            'pending'      => Peminjaman::where('status', 'pending')->count(),
            'dipinjam'     => Peminjaman::where('status', 'dipinjam')->count(),
            'terlambat'    => Peminjaman::where('status', 'terlambat')->count(),
            'dikembalikan' => Peminjaman::where('status', 'dikembalikan')->count(),
        ];

        return view('admin.peminjaman.index', compact('admin', 'peminjaman', 'stats'));
    }

    public function approve($id)
    {
        $peminjaman = Peminjaman::with('book', 'user')->findOrFail($id);

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Peminjaman sudah diproses sebelumnya!');
        }

        if ($peminjaman->book->stok <= 0) {
            return back()->with('error', 'Stok buku habis, tidak bisa disetujui!');
        }

        $peminjaman->update([
            'status'          => 'dipinjam',
            'tanggal_pinjam'  => now()->toDateString(),
            'tanggal_kembali' => now()->addDays(7)->toDateString(),
        ]);

        $peminjaman->book->decrement('stok');

        Notification::create([
            'user_id' => $peminjaman->user_id,
            'pesan'   => 'Peminjaman buku "'.$peminjaman->book->judul.'" telah disetujui. Batas pengembalian: '.now()->addDays(7)->format('d M Y').'.',
            'is_read' => false,
        ]);

        return back()->with('success', 'Peminjaman berhasil disetujui!');
    }

    public function reject($id)
    {
        $peminjaman = Peminjaman::with('book', 'user')->findOrFail($id);

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Peminjaman sudah diproses sebelumnya!');
        }

        $peminjaman->update(['status' => 'ditolak']);

        Notification::create([
            'user_id' => $peminjaman->user_id,
            'pesan'   => 'Maaf, peminjaman buku "'.$peminjaman->book->judul.'" ditolak oleh petugas.',
            'is_read' => false,
        ]);

        return back()->with('success', 'Peminjaman berhasil ditolak!');
    }

    public function returnBook($id)
    {
        $peminjaman = Peminjaman::with('book', 'user')->findOrFail($id);

        if (!in_array($peminjaman->status, ['dipinjam', 'terlambat'])) {
            return back()->with('error', 'Buku tidak dalam status dipinjam!');
        }

        // Hitung denda
        $denda = 0;
        if ($peminjaman->tanggal_kembali && now()->gt($peminjaman->tanggal_kembali)) {
            $hari = now()->diffInDays($peminjaman->tanggal_kembali);
            $denda = $hari * 2000;
        }

        $peminjaman->update([
            'status'               => 'dikembalikan',
            'tanggal_dikembalikan' => now(),
            'denda'                => $denda,
        ]);

        $peminjaman->book->increment('stok');

        Notification::create([
            'user_id' => $peminjaman->user_id,
            'pesan'   => 'Buku "'.$peminjaman->book->judul.'" berhasil dikembalikan.'
                .($denda > 0 ? ' Denda keterlambatan: Rp '.number_format($denda,0,',','.').'.' : ' Tepat waktu, terima kasih!'),
            'is_read' => false,
        ]);

        return back()->with('success', 'Buku berhasil dikembalikan!'
            .($denda > 0 ? ' Denda: Rp '.number_format($denda,0,',','.') : ''));
    }
}