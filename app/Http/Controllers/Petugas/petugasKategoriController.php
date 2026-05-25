<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class PetugasKategoriController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('petugas.kategori.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:categories,nama_kategori',
        ]);

        Category::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('petugas.kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Diubah: pakai $id, lalu cari manual pakai findOrFail
    public function edit($id)
    {
        $kategori = Category::findOrFail($id);
        return view('petugas.kategori.edit', compact('kategori'));
    }

    // Diubah: pakai $id, lalu cari manual
    public function update(Request $request, $id)
    {
        $kategori = Category::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:categories,nama_kategori,' . $kategori->id,
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('petugas.kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // Diubah: pakai $id, lalu cari manual
    public function destroy($id)
    {
        $kategori = Category::findOrFail($id);

        if ($kategori->books()->exists()) {
            return redirect()->back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki buku!');
        }

        $kategori->delete();
        return redirect()->route('petugas.kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}