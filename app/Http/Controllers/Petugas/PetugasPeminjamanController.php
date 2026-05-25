<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Book;
use Carbon\Carbon;

class PetugasPeminjamanController extends Controller
{
    public function validasi(Request $request)
    {
        $search = $request->get('search');

        $peminjaman = Peminjaman::with(['user', 'book'])
            ->where('status', 'pending')
            ->when($search, function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                })
                ->orWhereHas('book', function($q) use ($search) {
                    $q->where('title', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('petugas.peminjaman.validasi', compact('peminjaman', 'search'));
    }

    public function pengembalian(Request $request)
    {
        $search = $request->get('search');
        $tab = $request->get('tab', 'aktif');

        $peminjaman = Peminjaman::with(['user', 'book']);

        if ($tab == 'aktif') {
            $peminjaman = $peminjaman->whereIn('status', ['dipinjam', 'terlambat']);
        } elseif ($tab == 'terlambat') {
            $peminjaman = $peminjaman->where('status', 'terlambat')
                ->orWhere(function($q) {
                    $q->where('status', 'dipinjam')
                      ->where('tanggal_kembali', '<', now()->format('Y-m-d'));
                });
        } else {
            $peminjaman = $peminjaman->where('status', 'dikembalikan');
        }

        $peminjaman = $peminjaman->when($search, function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                })
                ->orWhereHas('book', function($q) use ($search) {
                    $q->where('title', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('petugas.peminjaman.pengembalian', compact('peminjaman', 'search', 'tab'));
    }

    public function approve($id)
    {
        $p = Peminjaman::findOrFail($id);

        if ($p->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini tidak bisa disetujui (bukan status pending)');
        }

        // Cek buku ada atau tidak
        if (!$p->book) {
            return back()->with('error', 'Buku tidak ditemukan dalam database');
        }

        // Cek stok — kalau null, set jadi 1 dulu
        $stock = $p->book->stock;
        if ($stock === null) {
            $p->book->update(['stock' => 1]);
            $stock = 1;
        }

        if ($stock <= 0) {
            return back()->with('error', 'Stok buku "' . $p->book->title . '" habis (stok: 0)');
        }

        $p->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => Carbon::now()->format('Y-m-d'),
            'tanggal_kembali' => Carbon::now()->addDays(14)->format('Y-m-d'),
        ]);

        $p->book->decrement('stock');

        return back()->with('success', 'Peminjaman "' . $p->book->title . '" disetujui untuk ' . $p->user->name);
    }

    public function reject($id)
    {
        $p = Peminjaman::findOrFail($id);

        if ($p->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini tidak bisa ditolak (bukan status pending)');
        }

        $p->update(['status' => 'ditolak']);

        return back()->with('success', 'Peminjaman ditolak');
    }

    public function returnBook($id)
    {
        $p = Peminjaman::findOrFail($id);

        if (!in_array($p->status, ['dipinjam', 'terlambat'])) {
            return back()->with('error', 'Buku ini tidak bisa dikembalikan (status: ' . $p->status . ')');
        }

        if (!$p->book) {
            return back()->with('error', 'Buku tidak ditemukan dalam database');
        }

        $p->update([
            'status' => 'dikembalikan',
            'tanggal_dikembalikan' => Carbon::now(),
        ]);

        $p->book->increment('stock');

        return back()->with('success', 'Buku "' . $p->book->title . '" berhasil dikembalikan oleh ' . $p->user->name);
    }
}