<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class UserPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'semua');

        $peminjaman = Peminjaman::with('book')
            ->where('user_id', Auth::id());

        if ($tab == 'aktif') {
            $peminjaman = $peminjaman->whereIn('status', ['pending', 'dipinjam', 'terlambat']);
        } elseif ($tab == 'selesai') {
            $peminjaman = $peminjaman->where('status', 'dikembalikan');
        } elseif ($tab == 'ditolak') {
            $peminjaman = $peminjaman->where('status', 'ditolak');
        }

        $peminjaman = $peminjaman->latest()->paginate(10);

        // Hitung jumlah per status
        $countSemua = Peminjaman::where('user_id', Auth::id())->count();
        $countAktif = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'dipinjam', 'terlambat'])->count();
        $countSelesai = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'dikembalikan')->count();
        $countDitolak = Peminjaman::where('user_id', Auth::id())
            ->where('status', 'ditolak')->count();

        return view('user.peminjaman', compact(
            'peminjaman', 'tab',
            'countSemua', 'countAktif', 'countSelesai', 'countDitolak'
        ));
    }

        public function cetakBukti($id)
    {
        $peminjaman = Peminjaman::with(['user', 'book'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.cetak-bukti', compact('peminjaman'));
    }
}
