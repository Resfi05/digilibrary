<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class PetugasBukuController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $books = Book::with('category')
            ->when($request->kategori, function($query) use ($request) {
                $query->where('kategori_id', $request->kategori);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Fix: 'stock' diubah jadi 'stok'
        $totalBooks = $books->count();
        $availableBooks = $books->sum('stok');
        $borrowedBooks = \App\Models\Peminjaman::where('status', 'dipinjam')->count();

        return view('petugas.buku.index', compact('books', 'categories', 'totalBooks', 'availableBooks', 'borrowedBooks'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('petugas.buku.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Fix: Nama validasi disesuaikan dengan input form (Bahasa Indonesia)
        $request->validate([
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:150',
            'penerbit'     => 'required|string|max:150',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn'         => 'nullable|string|max:20|unique:books,isbn',
            'kategori_id'  => 'required|exists:categories,id',
            'stok'         => 'required|integer|min:0',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only('judul', 'penulis', 'penerbit', 'tahun_terbit', 'isbn', 'kategori_id', 'stok');

        // Fix: Upload gambar pakai Storage agar sesuai dengan asset('storage/...')
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $path = $gambar->store('covers', 'public');
            $data['gambar'] = $path;
        }

        // Fix: HAPUS baris is_validated karena kolomnya tidak ada di database

        Book::create($data);

        return redirect()->route('petugas.buku.index')
            ->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit($id)
    {
        $buku = Book::findOrFail($id);
        $categories = Category::all();
        return view('petugas.buku.edit', compact('buku', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $buku = Book::findOrFail($id);

        // Fix: Nama validasi disesuaikan
        $request->validate([
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:150',
            'penerbit'     => 'required|string|max:150',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn'         => 'nullable|string|max:20|unique:books,isbn,' . $id,
            'kategori_id'  => 'required|exists:categories,id',
            'stok'         => 'required|integer|min:0',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only('judul', 'penulis', 'penerbit', 'tahun_terbit', 'isbn', 'kategori_id', 'stok');

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($buku->gambar) {
                Storage::disk('public')->delete($buku->gambar);
            }
            $gambar = $request->file('gambar');
            $path = $gambar->store('covers', 'public');
            $data['gambar'] = $path;
        }

        $buku->update($data);

        return redirect()->route('petugas.buku.index')
            ->with('success', 'Buku berhasil diperbarui');
    }

    public function destroy($id)
    {
        $buku = Book::findOrFail($id);

        if ($buku->peminjaman()->where('status', 'dipinjam')->count() > 0) {
            return redirect()->route('petugas.buku.index')
                ->with('error', 'Buku tidak bisa dihapus karena sedang dipinjam');
        }

        // Hapus gambar dari storage
        if ($buku->gambar) {
            Storage::disk('public')->delete($buku->gambar);
        }

        $buku->delete();

        return redirect()->route('petugas.buku.index')
            ->with('success', 'Buku berhasil dihapus');
    }
}