<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminDendaController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->user();

        $query = Peminjaman::with(['user', 'book'])
            ->where('denda', '>', 0);

        if ($request->status === 'lunas') {
            $query->where('bayar_denda', true);
        } elseif ($request->status === 'belum') {
            $query->where('bayar_denda', false);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', '%'.$request->search.'%'))
                  ->orWhereHas('book', fn($b) => $b->where('judul', 'like', '%'.$request->search.'%'));
            });
        }

        $denda = $query->latest()->paginate(15)->appends($request->query());

        $stats = [
            'total_denda'   => Peminjaman::where('denda', '>', 0)->sum('denda'),
            'belum_bayar'   => Peminjaman::where('denda', '>', 0)->where('bayar_denda', false)->sum('denda'),
            'sudah_bayar'   => Peminjaman::where('denda', '>', 0)->where('bayar_denda', true)->sum('denda'),
            'jumlah_kasus'  => Peminjaman::where('denda', '>', 0)->count(),
            'belum_kasus'   => Peminjaman::where('denda', '>', 0)->where('bayar_denda', false)->count(),
            'lunas_kasus'   => Peminjaman::where('denda', '>', 0)->where('bayar_denda', true)->count(),
        ];

        return view('admin.denda.index', compact('admin', 'denda', 'stats'));
    }

    public function bayar($id)
    {
        $peminjaman = Peminjaman::with('user', 'book')->findOrFail($id);

        if ($peminjaman->bayar_denda) {
            return back()->with('error', 'Denda sudah ditandai lunas sebelumnya!');
        }

        $peminjaman->update(['bayar_denda' => true]);

        Notification::create([
            'user_id' => $peminjaman->user_id,
            'pesan'   => 'Denda keterlambatan buku "'.$peminjaman->book->judul.'" sebesar Rp '.number_format($peminjaman->denda,0,',','.').' telah lunas. Terima kasih!',
            'is_read' => false,
        ]);

        return back()->with('success', 'Denda berhasil ditandai lunas!');
    }
}