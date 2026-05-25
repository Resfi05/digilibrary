<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Favorit - DigiLibrary</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        {!! file_get_contents(resource_path('css/app.css')) !!}
        {!! file_get_contents(resource_path('css/dashboard.css')) !!}
        {!! file_get_contents(resource_path('css/favorit.css')) !!}
    </style>
</head>
<body class="dash-body">

<aside class="dash-sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sb-icon">📚</div>
        <div><div class="sb-name">DigiLibrary</div><div class="sb-sub">Perpustakaan Digital</div></div>
    </div>
    <nav class="sidebar-nav">
        <a href="{{ route('user.dashboard') }}" class="sn-item"><span class="sn-icon">🏠</span><span>Beranda</span></a>
        <a href="{{ route('user.katalog') }}" class="sn-item"><span class="sn-icon">📖</span><span>Katalog Buku</span></a>
        <a href="{{ route('user.riwayat') }}" class="sn-item"><span class="sn-icon">🕐</span><span>Riwayat Peminjaman</span></a>
        <a href="{{ route('user.favorit') }}" class="sn-item active"><span class="sn-icon">❤️</span><span>Buku Favorit</span></a>
        <a href="{{ route('user.ulasan') }}" class="sn-item">💬</span><span>Ulasan Saya</span></a>
        <a href="{{ route('user.notifikasi') }}" class="sn-item">
            <span class="sn-icon">🔔</span><span>Notifikasi</span>
            @if($unreadNotif > 0)<span class="sn-badge">{{ $unreadNotif }}</span>@endif
        </a>
    </nav>
    <div class="sidebar-cta">
        <div class="scta-text"><strong>Baca lebih banyak,<br>wawasan lebih luas!</strong></div>
        <a href="{{ route('user.katalog') }}" class="scta-btn">Jelajahi Buku</a>
        <div class="scta-illustration">📚</div>
    </div>
</aside>

<div class="dash-main">
    <header class="dash-topbar">
        <div class="topbar-left">
            <button class="topbar-menu" onclick="toggleSidebar()">☰</button>
            <div class="topbar-search">
                <span>🔍</span>
                <input type="text" placeholder="Cari buku favorit...">
            </div>
        </div>
        <div class="topbar-right">
            <a href="#" class="topbar-notif">
                🔔@if($unreadNotif > 0)<span class="tn-badge">{{ $unreadNotif }}</span>@endif
            </a>
            <div class="topbar-user" onclick="toggleUserMenu()">
                @if($user->avatar)
        <img src="{{ asset('storage/'.$user->avatar) }}"
            style="width:34px;height:34px;border-radius:50%;object-fit:cover;border:2px solid #e5e7eb">
    @else
        <div class="tu-avatar">{{ strtoupper(substr($user->name,0,1)) }}</div>
    @endif
    <span class="tu-name">{{ explode(' ',$user->name)[0] }}</span>
    <span>▾</span>
                <div class="tu-dropdown" id="userMenu">
                    <a href="{{ route('user.profil') }}">👤 Profil Saya</a>
                    <a href="#">⚙️ Pengaturan</a>
                    <div class="tu-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf<button type="submit">🚪 Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="dash-content">
        <div class="favorit-layout">

            {{-- KONTEN UTAMA --}}
            <div class="favorit-main">
                <div class="favorit-header">
                    <div>
                        <h2><span class="fav-heart">❤️</span> Buku Favorit Saya</h2>
                        <p>Koleksi buku yang sudah Anda tandai sebagai favorit.</p>
                    </div>
                    <div class="fh-right">
                        <span class="fav-count">Menampilkan {{ $favorites->firstItem() ?? 0 }}-{{ $favorites->lastItem() ?? 0 }} dari {{ $favorites->total() }} buku favorit</span>
                        <form action="{{ route('user.favorit') }}" method="GET" style="display:inline">
                            <select name="sort" onchange="this.form.submit()" class="fav-sort">
                                <option value="terbaru" {{ request('sort','terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru Ditambahkan</option>
                                <option value="judul" {{ request('sort') == 'judul' ? 'selected' : '' }}>A-Z Judul</option>
                            </select>
                        </form>
                        <div class="fav-view-toggle">
                            <button class="fvt-btn fvt-active" onclick="setView('grid',this)">⊞</button>
                            <button class="fvt-btn" onclick="setView('list',this)">☰</button>
                        </div>
                    </div>
                </div>

                @if($favorites->count() > 0)
                <div class="favorit-grid" id="favGrid">
                    @foreach($favorites as $fav)
                    @php
                        $book = $fav->book;
                        $bcolors = ['#fef3c7','#dbeafe','#dcfce7','#fce7f3','#ede9fe','#ffedd5','#ecfdf5','#fdf2f8'];
                        $btcolors = ['#92400e','#1e40af','#166534','#9d174d','#5b21b6','#9a3412','#065f46','#831843'];
                        $idx = ($book->id ?? 0) % 8;
                        $rating = $book ? round($book->averageRating(), 1) : 0;
                    @endphp
                    <div class="fav-card" id="fav-card-{{ $fav->id }}">
                        <div class="fav-cover" style="background:{{ $bcolors[$idx] }}">
                            @if($book && $book->gambar)
                                <img src="{{ asset('storage/'.$book->gambar) }}" alt="{{ $book->judul }}">
                            @else
                                <div class="fav-cover-text" style="color:{{ $btcolors[$idx] }}">
                                    {{ strtoupper(substr($book->judul ?? 'B', 0, 2)) }}
                                </div>
                            @endif
                            <div class="fav-heart-badge">♥</div>
                        </div>
                        <div class="fav-info">
                            <div class="fav-cat">{{ $book->category->nama_kategori ?? '-' }}</div>
                            <div class="fav-title">{{ $book->judul ?? '-' }}</div>
                            <div class="fav-author">{{ $book->penulis ?? '-' }}</div>
                            <div class="fav-rating">
                                <span style="color:#f59e0b">★</span>
                                <span>{{ $rating > 0 ? $rating : 'Baru' }}</span>
                            </div>
                            <div class="fav-actions">
                                <a href="#" class="fav-btn-detail">Lihat Detail</a>
                                <button class="fav-btn-hapus" onclick="hapusFavorit({{ $fav->id }}, this)">🗑 Hapus</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div class="katalog-pagination" style="margin-top:24px">
                    @if($favorites->onFirstPage())
                        <span class="kp-btn kp-disabled">‹</span>
                    @else
                        <a href="{{ $favorites->previousPageUrl() }}" class="kp-btn">‹</a>
                    @endif
                    @foreach($favorites->getUrlRange(1, $favorites->lastPage()) as $page => $url)
                        @if($page == $favorites->currentPage())
                            <span class="kp-btn kp-active">{{ $page }}</span>
                        @elseif($page == 1 || $page == $favorites->lastPage() || abs($page - $favorites->currentPage()) <= 1)
                            <a href="{{ $url }}" class="kp-btn">{{ $page }}</a>
                        @elseif(abs($page - $favorites->currentPage()) == 2)
                            <span class="kp-dots">...</span>
                        @endif
                    @endforeach
                    @if($favorites->hasMorePages())
                        <a href="{{ $favorites->nextPageUrl() }}" class="kp-btn">›</a>
                    @else
                        <span class="kp-btn kp-disabled">›</span>
                    @endif
                </div>

                @else
                <div class="favorit-empty">
                    <div class="fe-icon">💔</div>
                    <h3>Belum ada buku favorit</h3>
                    <p>Tambahkan buku ke favorit dengan menekan ikon ♥ pada katalog buku.</p>
                    <a href="{{ route('user.katalog') }}" class="ke-btn">Jelajahi Katalog</a>
                </div>
                @endif
            </div>

            {{-- PANEL KANAN --}}
            <div class="favorit-right">
                {{-- Tentang Favorit --}}
                <div class="dr-card" style="text-align:center">
                    <div class="fr-heart-big">❤️</div>
                    <h4 class="fr-title">Tentang Favorit</h4>
                    <p class="fr-desc">Buku favorit memudahkan Anda menyimpan buku yang ingin Anda baca atau pinjam nanti.</p>
                </div>

                {{-- Statistik --}}
                <div class="dr-card">
                    <h4 class="dr-title">Statistik Favorit</h4>
                    <div class="summary-list">
                        <div class="sum-item">
                            <div class="sum-icon" style="background:#dbeafe">📖</div>
                            <div class="sum-info">Total Favorit</div>
                            <span class="sum-val" style="color:#1d4ed8">{{ $totalFav }}</span>
                        </div>
                        <div class="sum-item">
                            <div class="sum-icon" style="background:#fef3c7">⭐</div>
                            <div class="sum-info">Rata-rata Rating</div>
                            <span class="sum-val" style="color:#d97706">{{ $avgRating ? number_format($avgRating, 1) : '-' }}</span>
                        </div>
                        <div class="sum-item">
                            <div class="sum-icon" style="background:#dcfce7">📂</div>
                            <div class="sum-info">Kategori Terbanyak</div>
                            <span class="sum-val" style="color:#16a34a;font-size:.85rem">{{ $topKategori ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Rekomendasi --}}
                <div class="dr-card">
                    <h4 class="dr-title">Butuh Rekomendasi?</h4>
                    <p class="fr-desc">Temukan buku baru sesuai minat dan kategori favorit Anda.</p>
                    <a href="{{ route('user.katalog') }}" class="fr-explore-btn">
                        Jelajahi Katalog →
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function toggleSidebar() { document.getElementById('sidebar').classList.toggle('sidebar-open'); }
function toggleUserMenu() { document.getElementById('userMenu').classList.toggle('show'); }
document.addEventListener('click', function(e) {
    if (!e.target.closest('.topbar-user')) document.getElementById('userMenu')?.classList.remove('show');
});

function setView(type, btn) {
    document.querySelectorAll('.fvt-btn').forEach(b => b.classList.remove('fvt-active'));
    btn.classList.add('fvt-active');
    const grid = document.getElementById('favGrid');
    if (type === 'list') {
        grid.classList.add('favorit-list-view');
    } else {
        grid.classList.remove('favorit-list-view');
    }
}

function hapusFavorit(id, btn) {
    if (!confirm('Hapus dari favorit?')) return;
    const card = document.getElementById('fav-card-' + id);
    card.style.transition = 'all .3s ease';
    card.style.opacity = '0';
    card.style.transform = 'scale(.9)';
    setTimeout(() => card.remove(), 300);
}
</script>
</body>
</html>