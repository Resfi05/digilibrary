<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PetugasUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        $totalUsers = User::where('role', 'user')->count();
        $activeNow = Peminjaman::where('status', 'dipinjam')->distinct('user_id')->count('user_id');
        $newUsers = User::where('role', 'user')->whereMonth('created_at', now()->month)->count();

        return view('petugas.user.index', compact('users', 'totalUsers', 'activeNow', 'newUsers'));
    }

    public function detail($id)
    {
        $user = User::with(['peminjaman.book'])->findOrFail($id);
        
        return response()->json([
            'name'      => $user->name,
            'email'     => $user->email,
            'no_telp'   => $user->no_telp ?? '-',
            'alamat'    => $user->alamat ?? '-',
            'join_date' => $user->created_at->format('d M Y'),
            'total_pinjam' => $user->peminjaman->count(),
            'dipinjam_sekarang' => $user->peminjaman->where('status', 'dipinjam')->count(),
            'riwayat' => $user->peminjaman->latest()->take(3)->map(function($p) {
                return [
                    'judul'  => $p->book->judul ?? 'Buku Dihapus',
                    'status' => ucfirst($p->status),
                    'tanggal' => $p->tanggal_pinjam->format('d M Y')
                ];
            })
        ]);
    }
}