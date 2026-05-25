<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class AdminNotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->user();

        $query = Notification::with('user')->latest();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('pesan', 'like', '%'.$request->search.'%')
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%'.$request->search.'%'));
            });
        }

        if ($request->status === 'dibaca') {
            $query->where('is_read', true);
        } elseif ($request->status === 'belum') {
            $query->where('is_read', false);
        }

        $notifikasi = $query->paginate(15)->appends($request->query());

        $users = User::where('role', 'user')->orderBy('name')->get();

        $stats = [
            'total'  => Notification::count(),
            'belum'  => Notification::where('is_read', false)->count(),
            'dibaca' => Notification::where('is_read', true)->count(),
            'hari_ini' => Notification::whereDate('created_at', today())->count(),
        ];

        return view('admin.notifikasi.index', compact('admin', 'notifikasi', 'users', 'stats'));
    }

    public function kirim(Request $request)
    {
        $request->validate([
            'pesan'   => 'required|string|max:500',
            'target'  => 'required|in:semua,tertentu',
            'user_id' => 'required_if:target,tertentu|exists:users,id',
        ], [
            'pesan.required'   => 'Pesan notifikasi wajib diisi.',
            'user_id.required_if' => 'Pilih user tujuan.',
        ]);

        if ($request->target === 'semua') {
            $users = User::where('role', 'user')->get();
            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'pesan'   => $request->pesan,
                    'is_read' => false,
                ]);
            }
            $jumlah = $users->count();
            return back()->with('success', "Notifikasi berhasil dikirim ke {$jumlah} pengguna!");
        } else {
            Notification::create([
                'user_id' => $request->user_id,
                'pesan'   => $request->pesan,
                'is_read' => false,
            ]);
            $user = User::find($request->user_id);
            return back()->with('success', "Notifikasi berhasil dikirim ke {$user->name}!");
        }
    }

    public function destroy($id)
    {
        Notification::findOrFail($id)->delete();
        return back()->with('success', 'Notifikasi berhasil dihapus!');
    }

    public function hapusSemua(Request $request)
    {
        if ($request->target === 'dibaca') {
            Notification::where('is_read', true)->delete();
            return back()->with('success', 'Semua notifikasi yang sudah dibaca berhasil dihapus!');
        }
        Notification::truncate();
        return back()->with('success', 'Semua notifikasi berhasil dihapus!');
    }
}