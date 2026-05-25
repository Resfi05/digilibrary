<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->user();

        $query = Review::with(['user', 'book.category']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', '%'.$request->search.'%'))
                  ->orWhereHas('book', fn($b) => $b->where('judul', 'like', '%'.$request->search.'%'));
            });
        }

        if ($request->rating) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->latest()->paginate(15)->appends($request->query());

        $stats = [
            'total'    => Review::count(),
            'bintang5' => Review::where('rating', 5)->count(),
            'bintang4' => Review::where('rating', 4)->count(),
            'bintang3' => Review::where('rating', 3)->count(),
            'rendah'   => Review::where('rating', '<=', 2)->count(),
            'avg'      => round(Review::avg('rating'), 1),
        ];

        return view('admin.review.index', compact('admin', 'reviews', 'stats'));
    }

    public function destroy($id)
    {
        Review::findOrFail($id)->delete();
        return back()->with('success', 'Ulasan berhasil dihapus!');
    }
}