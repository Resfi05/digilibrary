<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Peminjaman;
use App\Models\Notification;
use App\Models\Favorite;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $unreadNotif = Notification::where('user_id', $user->id)->where('is_read', false)->count();

        $stats = [
            'dipinjam'     => Peminjaman::where('user_id', $user->id)->where('status', 'dipinjam')->count(),
            'pending'      => Peminjaman::where('user_id', $user->id)->where('status', 'pending')->count(),
            'terlambat'    => Peminjaman::where('user_id', $user->id)->where('status', 'terlambat')->count(),
            'dikembalikan' => Peminjaman::where('user_id', $user->id)->where('status', 'dikembalikan')->count(),
            'denda'        => Peminjaman::where('user_id', $user->id)->sum('denda'),
        ];

        $popularBooks = Book::with('category')
            ->where('stok', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(6)->get();

        $categories = Category::withCount('books')->get();

        $recentPeminjaman = Peminjaman::with('book.category')
            ->where('user_id', $user->id)
            ->latest()->take(3)->get();

        $notifications = Notification::where('user_id', $user->id)
            ->latest()->take(3)->get();

        return view('user.dashboard', compact(
            'user', 'stats', 'popularBooks', 'categories',
            'recentPeminjaman', 'notifications', 'unreadNotif'
        ));
    }

    public function profil()
    {
        $user = auth()->user();
        $unreadNotif = Notification::where('user_id', $user->id)->where('is_read', false)->count();

        $stats = [
            'dipinjam'     => Peminjaman::where('user_id', $user->id)->whereIn('status', ['dipinjam','terlambat'])->count(),
            'dikembalikan' => Peminjaman::where('user_id', $user->id)->where('status', 'dikembalikan')->count(),
            'favorit'      => Favorite::where('user_id', $user->id)->count(),
            'ulasan'       => Review::where('user_id', $user->id)->count(),
        ];

        $pinjaman = Peminjaman::with('book')
            ->where('user_id', $user->id)->latest()->take(3)->get()
            ->map(fn($p) => [
                'icon'  => $p->status === 'dikembalikan' ? '✅' : '📖',
                'tipe'  => $p->status === 'dikembalikan' ? 'kembali' : 'pinjam',
                'pesan' => ($p->status === 'dikembalikan' ? 'Mengembalikan' : 'Meminjam') . ' buku "' . ($p->book->judul ?? '-') . '"',
                'waktu' => $p->updated_at,
            ]);

        $favs = Favorite::with('book')
            ->where('user_id', $user->id)->latest()->take(2)->get()
            ->map(fn($f) => [
                'icon'  => '❤️',
                'tipe'  => 'favorit',
                'pesan' => 'Menambahkan "' . ($f->book->judul ?? '-') . '" ke favorit',
                'waktu' => $f->created_at,
            ]);

        $reviews = Review::with('book')
            ->where('user_id', $user->id)->latest()->take(2)->get()
            ->map(fn($r) => [
                'icon'  => '⭐',
                'tipe'  => 'ulasan',
                'pesan' => 'Memberikan ulasan untuk "' . ($r->book->judul ?? '-') . '"',
                'waktu' => $r->created_at,
            ]);

        $aktivitas = $pinjaman->concat($favs)->concat($reviews)
            ->sortByDesc('waktu')->take(5)->values();

        return view('user.profil', compact('user', 'stats', 'aktivitas', 'unreadNotif'));
    }

    public function katalog(Request $request)
    {
        $user = auth()->user();
        $categories = Category::withCount('books')->get();
        $unreadNotif = Notification::where('user_id', $user->id)->where('is_read', false)->count();

        $books = Book::with('category')
            ->when($request->search, fn($q) => $q->where('judul', 'like', '%'.$request->search.'%')
                ->orWhere('penulis', 'like', '%'.$request->search.'%'))
            ->when($request->kategori, fn($q) => $q->where('kategori_id', $request->kategori))
            ->when($request->status === 'tersedia', fn($q) => $q->where('stok', '>', 0))
            ->when($request->status === 'dipinjam', fn($q) => $q->where('stok', 0))
            ->when($request->sort === 'judul', fn($q) => $q->orderBy('judul'))
            ->when($request->sort === 'penulis', fn($q) => $q->orderBy('penulis'))
            ->when(!$request->sort || $request->sort === 'terbaru', fn($q) => $q->latest())
            ->paginate(8)->appends($request->query());

        $totalBooks = Book::count();

        return view('user.katalog', compact('user', 'books', 'categories', 'unreadNotif', 'totalBooks'));
    }

    public function riwayat(Request $request)
    {
        $user = auth()->user();
        $unreadNotif = Notification::where('user_id', $user->id)->where('is_read', false)->count();
        $status = $request->get('status', 'semua');

        $query = Peminjaman::with('book.category')->where('user_id', $user->id);
        if ($status !== 'semua') $query->where('status', $status);

        $peminjaman = $query->latest()->paginate(8)->appends($request->query());

        $ringkasan = [
            'dipinjam'     => Peminjaman::where('user_id', $user->id)->where('status', 'dipinjam')->count(),
            'pending'      => Peminjaman::where('user_id', $user->id)->where('status', 'pending')->count(),
            'terlambat'    => Peminjaman::where('user_id', $user->id)->where('status', 'terlambat')->count(),
            'dikembalikan' => Peminjaman::where('user_id', $user->id)->where('status', 'dikembalikan')->count(),
            'selesai'      => Peminjaman::where('user_id', $user->id)->where('status', 'dikembalikan')->count(),
        ];

        $nearDue = Peminjaman::with('book')
            ->where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_kembali', '<=', now()->addDays(3))
            ->get();

        return view('user.riwayat', compact('user', 'peminjaman', 'ringkasan', 'status', 'unreadNotif', 'nearDue'));
    }

    public function favorit(Request $request)
    {
        $user = auth()->user();
        $unreadNotif = Notification::where('user_id', $user->id)->where('is_read', false)->count();

        $favorites = Favorite::with('book.category')
            ->where('favorites.user_id', $user->id)
            ->when($request->sort === 'judul', fn($q) => $q->join('books','favorites.book_id','=','books.id')->orderBy('books.judul'))
            ->when(!$request->sort || $request->sort === 'terbaru', fn($q) => $q->latest('favorites.created_at'))
            ->paginate(8)->appends($request->query());

        $totalFav = Favorite::where('favorites.user_id', $user->id)->count();

        $avgRating = Favorite::where('favorites.user_id', $user->id)
            ->join('books', 'favorites.book_id', '=', 'books.id')
            ->join('reviews', 'books.id', '=', 'reviews.book_id')
            ->avg('reviews.rating');

        $topKategori = Favorite::where('favorites.user_id', $user->id)
            ->join('books', 'favorites.book_id', '=', 'books.id')
            ->join('categories', 'books.kategori_id', '=', 'categories.id')
            ->groupBy('categories.nama_kategori')
            ->orderByRaw('count(*) desc')
            ->value('categories.nama_kategori');

        return view('user.favorit', compact('user', 'favorites', 'totalFav', 'avgRating', 'topKategori', 'unreadNotif'));
    }

    public function ulasan(Request $request)
    {
        $user = auth()->user();
        $unreadNotif = Notification::where('user_id', $user->id)->where('is_read', false)->count();

        $reviews = Review::with('book.category')
            ->where('user_id', $user->id)
            ->when($request->sort === 'rating_tinggi', fn($q) => $q->orderBy('rating', 'desc'))
            ->when($request->sort === 'rating_rendah', fn($q) => $q->orderBy('rating', 'asc'))
            ->when(!$request->sort || $request->sort === 'terbaru', fn($q) => $q->latest())
            ->paginate(5)->appends($request->query());

        $totalUlasan = Review::where('user_id', $user->id)->count();
        $avgRating   = Review::where('user_id', $user->id)->avg('rating');
        $lastReview  = Review::with('book')->where('user_id', $user->id)->latest()->first();

        return view('user.ulasan', compact('user', 'reviews', 'totalUlasan', 'avgRating', 'lastReview', 'unreadNotif'));
    }

    public function notifikasi(Request $request)
    {
        $user = auth()->user();
        $unreadNotif = Notification::where('user_id', $user->id)->where('is_read', false)->count();
        $tipe = $request->get('tipe', 'semua');

        $query = Notification::where('user_id', $user->id);
        if ($tipe !== 'semua') $query->where('pesan', 'like', '%'.$tipe.'%');

        $notifications = $query->latest()->paginate(8)->appends($request->query());

        $ringkasan = [
            'peminjaman'   => Notification::where('user_id', $user->id)->where('pesan', 'like', '%pinjam%')->count(),
            'pengembalian' => Notification::where('user_id', $user->id)->where('pesan', 'like', '%kembali%')->count(),
            'pengingat'    => Notification::where('user_id', $user->id)->where('pesan', 'like', '%ingat%')->count(),
            'ulasan'       => Notification::where('user_id', $user->id)->where('pesan', 'like', '%ulasan%')->count(),
            'peringatan'   => Notification::where('user_id', $user->id)->where('pesan', 'like', '%terlambat%')->count(),
        ];

        return view('user.notifikasi', compact('user', 'notifications', 'ringkasan', 'tipe', 'unreadNotif'));
    }

    public function bacaSemua()
    {
        Notification::where('user_id', auth()->id())->update(['is_read' => true]);
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        auth()->user()->update($request->only('name', 'phone', 'address'));
        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function detailBuku($id)
    {
        $user = auth()->user();
        $unreadNotif = Notification::where('user_id', $user->id)->where('is_read', false)->count();

        $book = Book::with(['category', 'reviews.user'])->findOrFail($id);
        $rating = round($book->averageRating(), 1);
        $isFav = Favorite::where('user_id', $user->id)->where('book_id', $id)->exists();
        $sudahPinjam = Peminjaman::where('user_id', $user->id)
            ->where('book_id', $id)
            ->whereIn('status', ['pending', 'dipinjam'])->exists();

        $bukuLain = Book::with('category')
            ->where('kategori_id', $book->kategori_id)
            ->where('id', '!=', $id)->where('stok', '>', 0)
            ->take(4)->get();

        return view('user.detail-buku', compact('user', 'book', 'rating', 'isFav', 'sudahPinjam', 'bukuLain', 'unreadNotif'));
    }

    public function pinjamBuku(Request $request, $id)
    {
        $user = auth()->user();
        $book = Book::findOrFail($id);

        if ($book->stok <= 0) return back()->with('error', 'Stok buku habis!');

        $sudahPinjam = Peminjaman::where('user_id', $user->id)
            ->where('book_id', $id)
            ->whereIn('status', ['pending', 'dipinjam'])->exists();

        if ($sudahPinjam) return back()->with('error', 'Kamu sudah meminjam buku ini!');

        $durasi = in_array($request->durasi, [3, 7, 14]) ? (int)$request->durasi : 7;

        Peminjaman::create([
            'user_id'         => $user->id,
            'book_id'         => $id,
            'tanggal_pinjam'  => now(),
            'tanggal_kembali' => now()->addDays($durasi),
            'status'          => 'pending',
            'token'           => \Illuminate\Support\Str::random(20),
        ]);

        Notification::create([
            'user_id' => $user->id,
            'pesan'   => 'Permintaan pinjam buku "'.$book->judul.'" ('.$durasi.' hari) sedang diproses petugas.',
            'is_read' => false,
        ]);

        return redirect()->route('user.buku.detail', $id)->with('pinjam_sukses', [
            'judul'           => $book->judul,
            'penulis'         => $book->penulis,
            'kategori'        => $book->category->nama_kategori ?? '-',
            'durasi'          => $durasi,
            'tanggal_pinjam'  => now()->format('d M Y'),
            'tanggal_kembali' => now()->addDays($durasi)->format('d M Y'),
        ]);
    }

    public function toggleFavorit($id)
    {
        $user = auth()->user();
        $fav = Favorite::where('user_id', $user->id)->where('book_id', $id)->first();

        if ($fav) {
            $fav->delete();
            return back()->with('info', 'Buku dihapus dari favorit.');
        }

        Favorite::create(['user_id' => $user->id, 'book_id' => $id]);
        return back()->with('success', 'Buku ditambahkan ke favorit!');
    }

    public function ubahPassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->password_lama, auth()->user()->password)) {
            return back()->with('error_password', 'Password lama tidak benar!');
        }

        auth()->user()->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password_baru)]);
        return back()->with('success_password', 'Password berhasil diubah!');
    }

    public function ubahEmail(Request $request)
    {
        $request->validate(['email_baru' => 'required|email|unique:users,email']);
        auth()->user()->update(['email' => $request->email_baru]);
        return back()->with('success_email', 'Email berhasil diubah!');
    }

    public function ubahTelpon(Request $request)
    {
        $request->validate(['no_telp_baru' => 'required|string|max:20']);
        auth()->user()->update(['phone' => $request->no_telp_baru]);
        return back()->with('success_telpon', 'Nomor telepon berhasil diubah!');
    }

    public function ubahPreferensi(Request $request)
    {
        session(['bahasa' => $request->bahasa ?? 'Indonesia']);
        session(['mode_tampilan' => $request->mode_tampilan ?? 'Terang']);
        return back()->with('success_preferensi', 'Preferensi berhasil disimpan!');
    }

    public function simpanUlasan(Request $request, $book_id)
    {
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        if (Review::where('user_id', auth()->id())->where('book_id', $book_id)->exists()) {
            return back()->with('error', 'Kamu sudah memberikan ulasan untuk buku ini!');
        }

        if (!Peminjaman::where('user_id', auth()->id())->where('book_id', $book_id)->where('status', 'dikembalikan')->exists()) {
            return back()->with('error', 'Kamu hanya bisa mengulas buku yang sudah dikembalikan!');
        }

        Review::create([
            'user_id'  => auth()->id(),
            'book_id'  => $book_id,
            'rating'   => $request->rating,
            'komentar' => $request->komentar,
        ]);

        Notification::create([
            'user_id' => auth()->id(),
            'pesan'   => 'Ulasan kamu berhasil dikirim. Terima kasih!',
            'is_read' => false,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim!');
    }

    public function editUlasanSimpan(Request $request, $id)
    {
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        $review = Review::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $review->update(['rating' => $request->rating, 'komentar' => $request->komentar]);
        return back()->with('success', 'Ulasan berhasil diperbarui!');
    }

    public function hapusUlasan($id)
    {
        Review::where('id', $id)->where('user_id', auth()->id())->delete();
        return back()->with('success', 'Ulasan berhasil dihapus!');
    }

    public function cetakBukti($id)
    {
        $user = auth()->user();
        $peminjaman = Peminjaman::with('book.category')
            ->where('user_id', $user->id)->findOrFail($id);
        return view('user.bukti-peminjaman', compact('user', 'peminjaman'));
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = auth()->user();

        if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}