<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class AdminBukuController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->user();
        $query = Book::with('category');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%'.$request->search.'%')
                  ->orWhere('penulis', 'like', '%'.$request->search.'%')
                  ->orWhere('isbn', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->status === 'tersedia') {
            $query->where('stok', '>', 0);
        } elseif ($request->status === 'habis') {
            $query->where('stok', 0);
        }

        $buku = $query->latest()->paginate(10)->appends($request->query());
        $categories = Category::all();

        $stats = [
            'total'    => Book::count(),
            'tersedia' => Book::where('stok', '>', 0)->count(),
            'habis'    => Book::where('stok', 0)->count(),
            'kategori' => Category::count(),
        ];

        return view('admin.buku.index', compact('admin', 'buku', 'categories', 'stats'));
    }

    public function create()
    {
        $admin = auth()->user();
        $categories = Category::all();
        return view('admin.buku.create', compact('admin', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:150',
            'penerbit'      => 'required|string|max:150',
            'tahun_terbit'  => 'required|integer|min:1900|max:'.date('Y'),
            'isbn'          => 'nullable|string|max:20|unique:books,isbn',
            'kategori_id'   => 'required|exists:categories,id',
            'stok'          => 'required|integer|min:0',
            'deskripsi'     => 'nullable|string',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('gambar');

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('covers', 'public');
        }

        Book::create($data);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $admin = auth()->user();
        $buku = Book::findOrFail($id);
        $categories = Category::all();
        return view('admin.buku.edit', compact('admin', 'buku', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $buku = Book::findOrFail($id);

        $request->validate([
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:150',
            'penerbit'      => 'required|string|max:150',
            'tahun_terbit'  => 'required|integer|min:1900|max:'.date('Y'),
            'isbn'          => 'nullable|string|max:20|unique:books,isbn,'.$id,
            'kategori_id'   => 'required|exists:categories,id',
            'stok'          => 'required|integer|min:0',
            'deskripsi'     => 'nullable|string',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('gambar');

        if ($request->hasFile('gambar')) {
            if ($buku->gambar) Storage::disk('public')->delete($buku->gambar);
            $data['gambar'] = $request->file('gambar')->store('covers', 'public');
        }

        $buku->update($data);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $buku = Book::findOrFail($id);

        if ($buku->peminjaman()->whereIn('status', ['dipinjam','terlambat'])->count() > 0) {
            return back()->with('error', 'Buku tidak bisa dihapus karena sedang dipinjam!');
        }

        if ($buku->gambar) Storage::disk('public')->delete($buku->gambar);
        $buku->delete();

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus!');
    }

    public function validateBook($id)
    {
        Book::findOrFail($id)->update(['is_validated' => true]);
        return back()->with('success', 'Buku berhasil divalidasi!');
    }
}