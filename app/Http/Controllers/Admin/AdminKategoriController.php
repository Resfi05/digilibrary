<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class AdminKategoriController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->user();

        $kategori = Category::withCount('books')
            ->when($request->search, fn($q) => $q->where('nama_kategori', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        $stats = [
            'total'        => Category::count(),
            'ada_buku'     => Category::has('books')->count(),
            'kosong'       => Category::doesntHave('books')->count(),
            'total_buku'   => \App\Models\Book::count(),
        ];

        return view('admin.kategori.index', compact('admin', 'kategori', 'stats'));
    }

    public function create()
    {
        $admin = auth()->user();
        return view('admin.kategori.create', compact('admin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:categories,nama_kategori',
            'deskripsi'     => 'nullable|string|max:255',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah ada.',
        ]);

        Category::create($request->only('nama_kategori', 'deskripsi'));

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $admin = auth()->user();
        $kategori = Category::findOrFail($id);
        return view('admin.kategori.edit', compact('admin', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:categories,nama_kategori,'.$id,
            'deskripsi'     => 'nullable|string|max:255',
        ]);

        Category::findOrFail($id)->update($request->only('nama_kategori', 'deskripsi'));

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = Category::withCount('books')->findOrFail($id);

        if ($kategori->books_count > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki '.$kategori->books_count.' buku!');
        }

        $kategori->delete();
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}