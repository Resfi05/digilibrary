<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku - DigiLibrary</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        {!! file_get_contents(resource_path('css/app.css')) !!}
        {!! file_get_contents(resource_path('css/dashboard.css')) !!}
        {!! file_get_contents(resource_path('css/katalog.css')) !!}
    </style>
</head>
<body class="dash-body">

{{-- SIDEBAR --}}
<aside class="dash-sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sb-icon">📚</div>
        <div>
            <div class="sb-name">DigiLibrary</div>
            <div class="sb-sub">Perpustakaan Digital</div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <a href="{{ route('user.dashboard') }}" class="sn-item">
            <span class="sn-icon">🏠</span><span>Beranda</span>
        </a>
        <a href="{{ route('user.katalog') }}" class="sn-item active">
            <span class="sn-icon">📖</span><span>Katalog Buku</span>
        </a>
        <a href="{{ route('user.riwayat') }}" class="sn-item">
            <span class="sn-icon">🕐</span><span>Riwayat Peminjaman</span>
        </a>
        <a href="{{ route('user.favorit') }}" class="sn-item">
            <span class="sn-icon">❤️</span><span>Buku Favorit</span>
        </a>
        <a href="{{ route('user.ulasan') }}" class="sn-item">
            <span class="sn-icon">💬</span><span>Ulasan Saya</span>
        </a>
        <a href="{{ route('user.notifikasi') }}" class="sn-item">
            <span class="sn-icon">🔔</span><span>Notifikasi</span>
            @if($unreadNotif > 0)
            <span class="sn-badge">{{ $unreadNotif }}</span>
            @endif
        </a>
    </nav>
    <div class="sidebar-cta">
        <div class="scta-text"><strong>Baca lebih banyak,<br>wawasan lebih luas!</strong></div>
        <a href="{{ route('user.katalog') }}" class="scta-btn">Jelajahi Buku</a>
        <div class="scta-illustration">📚</div>
    </div>
</aside>

{{-- MAIN --}}
<div class="dash-main">

    {{-- TOPBAR --}}
    <header class="dash-topbar">
        <div class="topbar-left">
            <button class="topbar-menu" onclick="toggleSidebar()">☰</button>
            <form action="{{ route('user.katalog') }}" method="GET" class="topbar-search" style="flex:1;max-width:480px">
                <span>🔍</span>
                <input type="text" name="search" placeholder="Cari buku, penulis, atau kategori..." value="{{ request('search') }}">
            </form>
        </div>
        <div class="topbar-right">
            <a href="#" class="topbar-notif">
                🔔
                @if($unreadNotif > 0)<span class="tn-badge">{{ $unreadNotif }}</span>@endif
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
                        @csrf
                        <button type="submit">🚪 Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- CONTENT --}}
    <div class="dash-content">
        <div class="katalog-layout">

            {{-- KONTEN UTAMA --}}
            <div class="katalog-main">
                <div class="katalog-header">
                    <div>
                        <h2 class="katalog-title">Katalog Buku</h2>
                        <p class="katalog-sub">Temukan berbagai buku menarik untuk dibaca dan dipinjam.</p>
                    </div>
                </div>

                {{-- FILTER BAR --}}
                <form action="{{ route('user.katalog') }}" method="GET" id="filterForm">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="kategori" id="hiddenKategori" value="{{ request('kategori') }}">
                    <input type="hidden" name="status" id="hiddenStatus" value="{{ request('status') }}">

                    <div class="katalog-filterbar">
                        <div class="kfb-search">
                            <span>🔍</span>
                            <input type="text" name="search"
                                placeholder="Cari berdasarkan judul atau penulis..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="kfb-select">
                            <select name="kategori" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama_kategori }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="kfb-select">
                            <select name="sort" onchange="this.form.submit()">
                                <option value="terbaru" {{ request('sort','terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="judul" {{ request('sort') == 'judul' ? 'selected' : '' }}>A-Z Judul</option>
                                <option value="penulis" {{ request('sort') == 'penulis' ? 'selected' : '' }}>Penulis</option>
                            </select>
                        </div>
                        <button type="submit" class="kfb-btn">Cari</button>
                        @if(request('search') || request('kategori') || request('status'))
                        <a href="{{ route('user.katalog') }}" class="kfb-reset">Reset</a>
                        @endif
                    </div>
                </form>

                {{-- INFO HASIL --}}
                <div class="katalog-info">
                    <span>Menampilkan <strong>{{ $books->firstItem() ?? 0 }}-{{ $books->lastItem() ?? 0 }}</strong> dari <strong>{{ $books->total() }}</strong> buku</span>
                </div>

                {{-- GRID BUKU --}}
                @if($books->count() > 0)
                <div class="katalog-grid">
                    @foreach($books as $book)
                    @php $isFav = auth()->check() ? auth()->user()->favorites()->where('book_id',$book->id)->exists() : false; @endphp
                    @php
                        $bcolors = ['#fef3c7','#dbeafe','#dcfce7','#fce7f3','#ede9fe','#ffedd5','#ecfdf5','#fdf2f8'];
                        $btcolors = ['#92400e','#1e40af','#166534','#9d174d','#5b21b6','#9a3412','#065f46','#831843'];
                        $bc = $bcolors[$loop->index % 8];
                        $btc = $btcolors[$loop->index % 8];
                        $rating = round($book->averageRating(), 1);
                        $isFav = auth()->user()->favorites()->where('book_id', $book->id)->exists();
                    @endphp
                    <div class="kb-card">
                        {{-- Cover --}}
                        <div class="kb-cover" style="background:{{ $bc }}">
                            @if($book->gambar)
                                <img src="{{ asset('storage/'.$book->gambar) }}" alt="{{ $book->judul }}">
                            @else
                                <div class="kb-cover-text" style="color:{{ $btc }}">
                                    {{ strtoupper(substr($book->judul, 0, 2)) }}
                                </div>
                            @endif
                            {{-- Tombol favorit --}}
                            <form action="{{ route('user.buku.favorit', $book->id) }}" method="POST"
                            style="position:absolute;top:8px;right:8px">
                            @csrf
                            <button type="submit" class="kb-fav {{ $isFav ? 'kb-fav-active' : '' }}">♥</button>
                            </form>
                            {{-- Badge stok --}}
                            @if($book->stok == 0)
                            <div class="kb-badge-habis">Habis</div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="kb-info">
                            <div class="kb-cat">{{ $book->category->nama_kategori ?? '-' }}</div>
                            <div class="kb-title">{{ $book->judul }}</div>
                            <div class="kb-author">{{ $book->penulis }}</div>

                            <div class="kb-rating">
                                @for($i=1;$i<=5;$i++)
                                    <span class="{{ $i <= $rating ? 'kr-on' : 'kr-off' }}">★</span>
                                @endfor
                                <span class="kr-num">{{ $rating > 0 ? $rating : 'Baru' }}</span>
                            </div>

                            <div class="kb-stok">
                                <span class="ks-dot {{ $book->stok > 0 ? 'ks-green' : 'ks-red' }}"></span>
                                {{ $book->stok > 0 ? 'Stok Tersedia ('.$book->stok.')' : 'Tidak Tersedia' }}
                            </div>

                            @if($book->stok > 0)
                                <a href="{{ route('user.buku.detail', $book->id) }}" class="kb-btn">Lihat Detail</a>
                            @else
                                <span class="kb-btn kb-btn-off">Stok Habis</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div class="katalog-pagination">
                    @if($books->onFirstPage())
                        <span class="kp-btn kp-disabled">‹</span>
                    @else
                        <a href="{{ $books->previousPageUrl() }}" class="kp-btn">‹</a>
                    @endif

                    @foreach($books->getUrlRange(1, $books->lastPage()) as $page => $url)
                        @if($page == $books->currentPage())
                            <span class="kp-btn kp-active">{{ $page }}</span>
                        @elseif($page == 1 || $page == $books->lastPage() || abs($page - $books->currentPage()) <= 1)
                            <a href="{{ $url }}" class="kp-btn">{{ $page }}</a>
                        @elseif(abs($page - $books->currentPage()) == 2)
                            <span class="kp-dots">...</span>
                        @endif
                    @endforeach

                    @if($books->hasMorePages())
                        <a href="{{ $books->nextPageUrl() }}" class="kp-btn">›</a>
                    @else
                        <span class="kp-btn kp-disabled">›</span>
                    @endif
                </div>

                @else
                <div class="katalog-empty">
                    <div class="ke-emoji">📭</div>
                    <h3>Buku tidak ditemukan</h3>
                    <p>Coba kata kunci lain atau reset filter pencarian</p>
                    <a href="{{ route('user.katalog') }}" class="ke-btn">Reset Pencarian</a>
                </div>
                @endif
            </div>

            {{-- SIDEBAR FILTER KANAN --}}
            <div class="katalog-filter">
                <div class="kf-card">
                    <h4 class="kf-title">Filter</h4>

                    {{-- Kategori --}}
                    <div class="kf-section">
                        <div class="kfs-title">Kategori</div>
                        <div class="kfs-list">
                            <label class="kfs-item {{ !request('kategori') ? 'kfs-active' : '' }}"
                                onclick="setFilter('kategori', '')">
                                <span class="kfs-check">{{ !request('kategori') ? '☑' : '☐' }}</span>
                                <span>Semua Kategori</span>
                                <span class="kfs-count">{{ $totalBooks }}</span>
                            </label>
                            @foreach($categories as $cat)
                            <label class="kfs-item {{ request('kategori') == $cat->id ? 'kfs-active' : '' }}"
                                onclick="setFilter('kategori', '{{ $cat->id }}')">
                                <span class="kfs-check">{{ request('kategori') == $cat->id ? '☑' : '☐' }}</span>
                                <span>{{ $cat->nama_kategori }}</span>
                                <span class="kfs-count">{{ $cat->books_count }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="kf-section">
                        <div class="kfs-title">Status</div>
                        <label class="kfs-item {{ request('status') == 'tersedia' ? 'kfs-active' : '' }}"
                            onclick="setFilter('status', '{{ request('status') == 'tersedia' ? '' : 'tersedia' }}')">
                            <span class="kfs-check">{{ request('status') == 'tersedia' ? '☑' : '☐' }}</span>
                            <span>Tersedia</span>
                        </label>
                        <label class="kfs-item {{ request('status') == 'dipinjam' ? 'kfs-active' : '' }}"
                            onclick="setFilter('status', '{{ request('status') == 'dipinjam' ? '' : 'dipinjam' }}')">
                            <span class="kfs-check">{{ request('status') == 'dipinjam' ? '☑' : '☐' }}</span>
                            <span>Dipinjam</span>
                        </label>
                    </div>

                    <button onclick="applyFilter()" class="kf-apply">
                        ⚡ Terapkan Filter
                    </button>

                    @if(request('kategori') || request('status'))
                    <a href="{{ route('user.katalog') }}" class="kf-reset">Reset Filter</a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('sidebar-open');
}
function toggleUserMenu() {
    document.getElementById('userMenu').classList.toggle('show');
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.topbar-user')) {
        document.getElementById('userMenu')?.classList.remove('show');
    }
});

function setFilter(type, value) {
    document.getElementById('hidden' + type.charAt(0).toUpperCase() + type.slice(1)).value = value;
}

function applyFilter() {
    document.getElementById('filterForm').submit();
}

</script>
</body>
</html>