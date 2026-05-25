<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;

class PetugasDendaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $tab = $request->get('tab', 'belum');

        $peminjaman = Peminjaman::with(['user', 'book']);

        if ($tab == 'belum') {
            $peminjaman = $peminjaman->where('status', 'dikembalikan')
                ->where('bayar_denda', false)
                ->whereRaw('denda > 0');
        } elseif ($tab == 'sudah') {
            $peminjaman = $peminjaman->where('bayar_denda', true);
        } else {
            $peminjaman = $peminjaman->whereRaw('denda > 0');
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

        // Stats
        $totalDenda = Peminjaman::whereRaw('denda > 0')->sum('denda');
        $belumBayar = Peminjaman::where('bayar_denda', false)->whereRaw('denda > 0')->sum('denda');
        $sudahBayar = Peminjaman::where('bayar_denda', true)->sum('denda');
        $countBelum = Peminjaman::where('bayar_denda', false)->whereRaw('denda > 0')->count();
        $countSudah = Peminjaman::where('bayar_denda', true)->count();
        $rataRata = $countBelum + $countSudah > 0 ? $totalDenda / ($countBelum + $countSudah) : 0;

        return view('petugas.denda.index', compact(
            'peminjaman', 'search', 'tab',
            'totalDenda', 'belumBayar', 'sudahBayar', 'rataRata',
            'countBelum', 'countSudah'
        ));
    }

    public function bayar($id)
    {
        $p = Peminjaman::findOrFail($id);
        $p->update(['bayar_denda' => true]);
        return back()->with('success', 'Denda berhasil ditandai sudah dibayar');
    }
}