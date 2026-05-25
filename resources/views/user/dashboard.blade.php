<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DigiLibrary</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        {!! file_get_contents(resource_path('css/app.css')) !!}
        {!! file_get_contents(resource_path('css/dashboard.css')) !!}
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
        <a href="{{ route('user.dashboard') }}" class="sn-item active">
            <span class="sn-icon">🏠</span><span>Beranda</span>
        </a>
        <a href="{{ route('user.katalog') }}" class="sn-item">
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

    {{-- TOPBAR (tanpa search) --}}
    <header class="dash-topbar">
        <div class="topbar-left">
            <button class="topbar-menu" onclick="toggleSidebar()">☰</button>
            <div class="topbar-brand-mobile">
                <span class="sb-icon" style="font-size:1.2rem">📚</span>
                <span style="font-weight:700;font-size:.95rem;color:#111827">Digi<strong style="color:#1a56db">Library</strong></span>
            </div>
        </div>
        <div class="topbar-right">
            <a href="{{ route('user.notifikasi') }}" class="topbar-notif">
                🔔
                @if($unreadNotif > 0)
                    <span class="tn-badge">{{ $unreadNotif }}</span>
                @endif
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
        <div class="dash-grid">

            {{-- KOLOM TENGAH --}}
            <div class="dash-center">

                {{-- WELCOME BANNER --}}
                <div class="welcome-banner">
                    <div class="wb-text">
                        <p class="wb-greeting">Selamat datang kembali,</p>
                        <h2 class="wb-name">{{ $user->name }}! 👋</h2>
                        <p class="wb-desc">Temukan buku favoritmu dan perluas pengetahuanmu setiap hari.</p>
                        <a href="{{ route('user.katalog') }}" class="wb-btn">Jelajahi Katalog</a>
                    </div>
                </div>

                {{-- BUKU POPULER --}}
                <div class="dash-section">
                    <div class="ds-header">
                        <h3>Buku Populer</h3>
                        <a href="{{ route('user.katalog') }}" class="ds-more">Lihat semua →</a>
                    </div>
                    <div class="popular-books-grid">
                        @forelse($popularBooks as $book)
                        @php
                            $bcolors = ['#fef3c7','#dbeafe','#dcfce7','#fce7f3','#ede9fe','#ffedd5'];
                            $btcolors = ['#92400e','#1e40af','#166534','#9d174d','#5b21b6','#9a3412'];
                            $bc = $bcolors[$loop->index % 6];
                            $btc = $btcolors[$loop->index % 6];
                            $rating = round($book->averageRating(), 1);
                        @endphp
                        <div class="pb-card">
                            <div class="pb-cover" style="background:{{ $bc }}">
                                @if($book->gambar)
                                    <img src="{{ asset('storage/'.$book->gambar) }}" alt="{{ $book->judul }}">
                                @else
                                    <span style="font-size:1.8rem;font-weight:900;color:{{ $btc }};opacity:.5">
                                        {{ strtoupper(substr($book->judul,0,2)) }}
                                    </span>
                                @endif
                            </div>
                            <div class="pb-info">
                                <div class="pb-cat">{{ $book->category->nama_kategori ?? '-' }}</div>
                                <div class="pb-title">{{ $book->judul }}</div>
                                <div class="pb-author">{{ $book->penulis }}</div>
                                <div class="pb-meta">
                                    <div class="pb-rating">
                                        <span style="color:#f59e0b">★</span>
                                        <span>{{ $rating > 0 ? $rating : 'Baru' }}</span>
                                    </div>
                                    <span class="pb-stok {{ $book->stok > 0 ? 'stok-ok' : 'stok-no' }}">
                                        {{ $book->stok > 0 ? 'Tersedia' : 'Habis' }}
                                    </span>
                                </div>
                                <a href="{{ route('user.buku.detail', $book->id) }}" class="pb-btn">Lihat Detail</a>
                            </div>
                        </div>
                        @empty
                        <p style="color:var(--gray-500)">Belum ada buku tersedia.</p>
                        @endforelse
                    </div>
                </div>

                {{-- KATEGORI --}}
                <div class="dash-section">
                    <div class="ds-header">
                        <h3>Kategori Buku</h3>
                        <a href="{{ route('user.katalog') }}" class="ds-more">Lihat semua →</a>
                    </div>
                    <div class="dash-categories">
                        @php
                        $catIcons=['Fiksi'=>'📚','Non-Fiksi'=>'📖','Sejarah'=>'🏛️','Biografi'=>'👤','Ilmu Pengetahuan'=>'🔬','Teknologi'=>'💻','Agama'=>'🕌','Sastra'=>'✍️','Hukum'=>'⚖️','Kesehatan'=>'🏥'];
                        $catColors=[
                            'Fiksi'=>['#eff6ff','#bfdbfe','#1d4ed8'],
                            'Non-Fiksi'=>['#fdf4ff','#e9d5ff','#7e22ce'],
                            'Sejarah'=>['#f0fdf4','#bbf7d0','#15803d'],
                            'Biografi'=>['#fff7ed','#fed7aa','#c2410c'],
                            'Ilmu Pengetahuan'=>['#f0f9ff','#bae6fd','#0369a1'],
                            'Teknologi'=>['#ecfdf5','#a7f3d0','#065f46'],
                            'Agama'=>['#fefce8','#fde68a','#854d0e'],
                            'Sastra'=>['#fdf2f8','#f9a8d4','#9d174d'],
                            'Hukum'=>['#f8fafc','#cbd5e1','#334155'],
                            'Kesehatan'=>['#fff1f2','#fecdd3','#b91c1c'],
                        ];
                        @endphp
                        @foreach($categories->take(6) as $cat)
                        @php
                            $cc = $catColors[$cat->nama_kategori] ?? ['#f3f4f6','#e5e7eb','#374151'];
                            $ci = $catIcons[$cat->nama_kategori] ?? '📚';
                        @endphp
                        <a href="{{ route('user.katalog', ['kategori'=>$cat->id]) }}" class="dash-cat-card"
                           style="background:{{ $cc[0] }};border-color:{{ $cc[1] }}">
                            <div class="dcc-icon" style="background:{{ $cc[1] }}">{{ $ci }}</div>
                            <div class="dcc-name" style="color:{{ $cc[2] }}">{{ $cat->nama_kategori }}</div>
                            <div class="dcc-count">{{ $cat->books_count }} buku</div>
                        </a>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN --}}
            <div class="dash-right">

                {{-- RINGKASAN --}}
                <div class="dr-card">
                    <h4 class="dr-title">Ringkasan Saya</h4>
                    <div class="summary-list">
                        <div class="sum-item">
                            <div class="sum-icon" style="background:#dbeafe">📖</div>
                            <div class="sum-info">Sedang Dipinjam</div>
                            <span class="sum-val" style="color:#1d4ed8">{{ $stats['dipinjam'] }}</span>
                        </div>
                        <div class="sum-item">
                            <div class="sum-icon" style="background:#fef3c7">⏳</div>
                            <div class="sum-info">Menunggu Konfirmasi</div>
                            <span class="sum-val" style="color:#d97706">{{ $stats['pending'] }}</span>
                        </div>
                        <div class="sum-item">
                            <div class="sum-icon" style="background:#fee2e2">⚠️</div>
                            <div class="sum-info">Terlambat</div>
                            <span class="sum-val" style="color:#dc2626">{{ $stats['terlambat'] }}</span>
                        </div>
                        <div class="sum-item">
                            <div class="sum-icon" style="background:#dcfce7">💰</div>
                            <div class="sum-info">Denda</div>
                            <span class="sum-val" style="color:#16a34a">
                                Rp {{ number_format($stats['denda'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- PEMINJAMAN TERBARU --}}
                <div class="dr-card">
                    <div class="dr-header">
                        <h4 class="dr-title">Peminjaman Terbaru</h4>
                        <a href="{{ route('user.riwayat') }}" class="dr-more">Lihat semua →</a>
                    </div>
                    @forelse($recentPeminjaman as $p)
                    <div class="recent-item">
                        <div class="ri-cover">
                            {{ strtoupper(substr($p->book->judul ?? 'B', 0, 1)) }}
                        </div>
                        <div class="ri-info">
                            <div class="ri-title">{{ $p->book->judul ?? '-' }}</div>
                            <div class="ri-author">{{ $p->book->penulis ?? '-' }}</div>
                            <div class="ri-date">
                                {{ $p->tanggal_pinjam ? $p->tanggal_pinjam->format('d M Y') : '-' }}
                            </div>
                        </div>
                        <span class="ri-status
                            @if($p->status=='dipinjam') status-pinjam
                            @elseif($p->status=='pending') status-pending
                            @elseif($p->status=='terlambat') status-terlambat
                            @elseif($p->status=='dikembalikan') status-kembali
                            @else status-tolak @endif">
                            {{ ucfirst($p->status) }}
                        </span>
                    </div>
                    @empty
                    <div class="empty-small">Belum ada peminjaman</div>
                    @endforelse
                </div>

                {{-- NOTIFIKASI --}}
                <div class="dr-card">
                    <div class="dr-header">
                        <h4 class="dr-title">Notifikasi</h4>
                        <a href="{{ route('user.notifikasi') }}" class="dr-more">Lihat semua →</a>
                    </div>
                    @forelse($notifications as $notif)
                    <div class="notif-item {{ $notif->is_read ? '' : 'notif-unread' }}">
                        <div class="notif-icon">
                            @if(str_contains($notif->pesan, 'disetujui')) ✅
                            @elseif(str_contains($notif->pesan, 'terlambat')) ⚠️
                            @elseif(str_contains($notif->pesan, 'denda')) 💰
                            @else 🔔 @endif
                        </div>
                        <div class="notif-content">
                            <div class="notif-msg">{{ $notif->pesan }}</div>
                            <div class="notif-time">
                                {{ $notif->created_at ? \Carbon\Carbon::parse($notif->created_at)->diffForHumans() : '' }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-small">Tidak ada notifikasi</div>
                    @endforelse
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
        document.getElementById('userMenu').classList.remove('show');
    }
});
</script>
</body>
</html>