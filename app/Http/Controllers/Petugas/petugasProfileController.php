<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PetugasProfileController extends Controller
{
    public function index()
    {
        $petugas = Auth::user();
        return view('petugas.profile.index', compact('petugas'));
    }

    public function update(Request $request)
    {
        $petugas = Auth::user();

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $petugas->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        $petugas->update($request->only('name', 'email', 'phone', 'address'));

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function ubahPassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:8|confirmed',
        ]);

        $petugas = Auth::user();

        if (!Hash::check($request->password_lama, $petugas->password)) {
            return back()->with('error', 'Password lama salah');
        }

        $petugas->update(['password' => Hash::make($request->password_baru)]);

        return back()->with('success', 'Password berhasil diubah');
    }
}