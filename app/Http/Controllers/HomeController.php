<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $books = Book::with('category')
            ->when($request->kategori, fn($q) => $q->where('kategori_id', $request->kategori))
            ->when($request->search, fn($q) =>
                $q->where('judul', 'like', '%'.$request->search.'%')
                  ->orWhere('penulis', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate(12);

        // Buku populer: diurutkan berdasarkan jumlah peminjaman
        $bukuPopuler = Book::with('category')
            ->withCount('peminjaman')
            ->orderByDesc('peminjaman_count')
            ->take(6)
            ->get();

        return view('home', compact('books', 'categories', 'bukuPopuler'));
    }
}