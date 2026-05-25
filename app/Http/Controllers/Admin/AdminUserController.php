<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // ===== DAFTAR USER (role: user) =====
    public function index(Request $request)
    {
        $admin = auth()->user();
        $query = User::where('role', 'user');

        if ($request->status === 'aktif')    $query->where('is_active', true);
        if ($request->status === 'nonaktif') $query->where('is_active', false);
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $users = $query->latest()->paginate(10)->appends($request->query());
        $stats = [
            'total'    => User::where('role', 'user')->count(),
            'aktif'    => User::where('role', 'user')->where('is_active', true)->count(),
            'nonaktif' => User::where('role', 'user')->where('is_active', false)->count(),
        ];

        return view('admin.user.index', compact('admin', 'users', 'stats'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
        ]);
        $user->update($request->only('name', 'email'));
        return back()->with('success', 'Data user berhasil diperbarui!');
    }

    public function toggleStatus($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun berhasil {$status}!");
    }

    public function destroy($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }

    // ===== DAFTAR PETUGAS (role: petugas) =====
    public function petugasList(Request $request)
    {
        $admin = auth()->user();
        $query = User::where('role', 'petugas');

        if ($request->status === 'aktif')    $query->where('is_active', true);
        if ($request->status === 'nonaktif') $query->where('is_active', false);
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $users = $query->latest()->paginate(10)->appends($request->query());
        $stats = [
            'total'    => User::where('role', 'petugas')->count(),
            'aktif'    => User::where('role', 'petugas')->where('is_active', true)->count(),
            'nonaktif' => User::where('role', 'petugas')->where('is_active', false)->count(),
        ];

        return view('admin.user.petugas', compact('admin', 'users', 'stats'));
    }

    public function petugasUpdate(Request $request, $id)
    {
        $user = User::where('role', 'petugas')->findOrFail($id);
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
        ]);
        $user->update($request->only('name', 'email'));
        return back()->with('success', 'Data petugas berhasil diperbarui!');
    }

    public function petugasToggle($id)
    {
        $user = User::where('role', 'petugas')->findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun petugas berhasil {$status}!");
    }

    public function petugasDestroy($id)
    {
        $user = User::where('role', 'petugas')->findOrFail($id);
        $user->delete();
        return back()->with('success', 'Petugas berhasil dihapus!');
    }
}