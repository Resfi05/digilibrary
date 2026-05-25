<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $book->judul }} - DigiLibrary</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        {!! file_get_contents(resource_path('css/app.css')) !!}
        {!! file_get_contents(resource_path('css/dashboard.css')) !!}
        {!! file_get_contents(resource_path('css/detail-buku.css')) !!}
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
        <a href="{{ route('user.katalog') }}" class="sn-item active"><span class="sn-icon">📖</span><span>Katalog Buku</span></a>
        <a href="{{ route('user.riwayat') }}" class="sn-item"><span class="sn-icon">🕐</span><span>Riwayat Peminjaman</span></a>
        <a href="{{ route('user.favorit') }}" class="sn-item"><span class="sn-icon">❤️</span><span>Buku Favorit</span></a>
        <a href="{{ route('user.ulasan') }}" class="sn-item"><span class="sn-icon">💬</span><span>Ulasan Saya</span></a>
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
                <input type="text" placeholder="Cari buku, penulis, atau kategori...">
            </div>
        </div>
        <div class="topbar-right">
            <a href="{{ route('user.notifikasi') }}" class="topbar-notif">
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

        {{-- BREADCRUMB --}}
        <div class="breadcrumb">
            <a href="{{ route('user.dashboard') }}">Beranda</a>
            <span>›</span>
            <a href="{{ route('user.katalog') }}">Katalog Buku</a>
            <span>›</span>
            <span>{{ $book->judul }}</span>
        </div>

        {{-- ALERT --}}
        @if(session('success'))
        <div class="db-alert db-alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="db-alert db-alert-error">✕ {{ session('error') }}</div>
        @endif
        @if(session('info'))
        <div class="db-alert db-alert-info">ℹ {{ session('info') }}</div>
        @endif

        <div class="detail-layout">

            {{-- KONTEN UTAMA --}}
            <div class="detail-main">

                {{-- INFO BUKU --}}
                <div class="db-card">
                    <div class="db-top">
                        {{-- Cover --}}
                        @php
                            $bcolors = ['#fef3c7','#dbeafe','#dcfce7','#fce7f3','#ede9fe','#ffedd5'];
                            $btcolors = ['#92400e','#1e40af','#166534','#9d174d','#5b21b6','#9a3412'];
                            $idx = $book->id % 6;
                        @endphp
                        <div class="db-cover" style="background:{{ $bcolors[$idx] }}">
                            @if($book->gambar)
                                <img src="{{ asset('storage/'.$book->gambar) }}" alt="{{ $book->judul }}">
                            @else
                                <div class="db-cover-inner">
                                    <div class="db-cover-title" style="color:{{ $btcolors[$idx] }}">
                                        {{ $book->judul }}
                                    </div>
                                    <div class="db-cover-author" style="color:{{ $btcolors[$idx] }}">
                                        {{ $book->penulis }}
                                    </div>
                                    <div class="db-cover-deco" style="background:{{ $btcolors[$idx] }};opacity:.15"></div>
                                </div>
                            @endif
                        </div>

                        {{-- Detail --}}
                        <div class="db-detail">
                            <div class="db-cat">{{ $book->category->nama_kategori ?? '-' }}</div>
                            <h1 class="db-title">{{ $book->judul }}</h1>
                            <div class="db-author">{{ $book->penulis }}</div>

                            <div class="db-rating-row">
                                <div class="db-stars">
                                    @for($i=1;$i<=5;$i++)
                                        <span class="{{ $i <= $rating ? 'ds-on' : 'ds-off' }}">★</span>
                                    @endfor
                                </div>
                                <span class="db-rating-num">{{ $rating > 0 ? $rating : '0.0' }}</span>
                                <span class="db-review-count">({{ $book->reviews->count() }} ulasan)</span>
                                <span class="db-cat-badge">{{ $book->category->nama_kategori ?? '-' }}</span>
                            </div>

                            <div class="db-desc" id="descText">
                                {{ $book->judul }} adalah buku karya {{ $book->penulis }}
                                @if($book->penerbit) yang diterbitkan oleh {{ $book->penerbit }}. @endif
                                Buku ini termasuk dalam kategori {{ $book->category->nama_kategori ?? '-' }}.
                                @if($book->isbn) ISBN: {{ $book->isbn }}. @endif
                                Tersedia {{ $book->stok }} eksemplar untuk dipinjam.
                            </div>

                            <div class="db-meta-grid">
                                <div class="dbm-item">
                                    <span class="dbm-icon">👤</span>
                                    <div>
                                        <div class="dbm-label">Penulis</div>
                                        <div class="dbm-val">{{ $book->penulis }}</div>
                                    </div>
                                </div>
                                <div class="dbm-item">
                                    <span class="dbm-icon">🏢</span>
                                    <div>
                                        <div class="dbm-label">Penerbit</div>
                                        <div class="dbm-val">{{ $book->penerbit ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="dbm-item">
                                    <span class="dbm-icon">📚</span>
                                    <div>
                                        <div class="dbm-label">Kategori</div>
                                        <div class="dbm-val">{{ $book->category->nama_kategori ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="dbm-item">
                                    <span class="dbm-icon">🔢</span>
                                    <div>
                                        <div class="dbm-label">ISBN</div>
                                        <div class="dbm-val">{{ $book->isbn ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="dbm-item">
                                    <span class="dbm-icon">📦</span>
                                    <div>
                                        <div class="dbm-label">Stok</div>
                                        <div class="dbm-val {{ $book->stok > 0 ? 'text-green' : 'text-red' }}">
                                            {{ $book->stok > 0 ? $book->stok.' tersedia' : 'Habis' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="dbm-item">
                                    <span class="dbm-icon">⭐</span>
                                    <div>
                                        <div class="dbm-label">Rating</div>
                                        <div class="dbm-val">{{ $rating > 0 ? $rating.'/5.0' : 'Belum ada' }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol aksi mobile --}}
                            <div class="db-actions-mobile">
                                @if($sudahPinjam)
                                    <button class="dba-btn dba-btn-disabled" disabled>📋 Sedang Dipinjam</button>
                                @elseif($book->stok > 0)
                                    <button onclick="openModal()" class="dba-btn dba-btn-pinjam" style="width:100%;margin-top:12px">
                                        📅 Pinjam Buku
                                    </button>
                                @else
                                    <button class="dba-btn dba-btn-disabled" disabled>Stok Habis</button>
                                @endif
                                <form action="{{ route('user.buku.favorit', $book->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dba-btn dba-btn-fav {{ $isFav ? 'dba-fav-active' : '' }}">
                                        {{ $isFav ? '❤️ Hapus Favorit' : '🤍 Tambah Favorit' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- TABS --}}
                    <div class="db-tabs">
                        <button class="dbt-btn dbt-active" onclick="showTab('deskripsi',this)">Deskripsi</button>
                        <button class="dbt-btn" onclick="showTab('informasi',this)">Informasi Buku</button>
                        <button class="dbt-btn" onclick="showTab('ulasan',this)">Ulasan ({{ $book->reviews->count() }})</button>
                    </div>

                    {{-- TAB: DESKRIPSI --}}
                    <div class="db-tab-content" id="tab-deskripsi">
                        <div class="dbt-section">
                            <h4>Tentang Buku</h4>
                            <p>{{ $book->judul }} adalah karya tulis dari {{ $book->penulis }}
                            @if($book->penerbit), diterbitkan oleh {{ $book->penerbit }}@endif.
                            Buku ini tersedia dalam koleksi perpustakaan digital DigiLibrary
                            dengan {{ $book->stok }} eksemplar yang dapat dipinjam.</p>
                            <p>Kategori buku ini adalah <strong>{{ $book->category->nama_kategori ?? '-' }}</strong>.
                            Dengan meminjam buku ini, Anda dapat memperluas wawasan dan pengetahuan Anda.</p>
                        </div>
                    </div>

                    {{-- TAB: INFORMASI --}}
                    <div class="db-tab-content" id="tab-informasi" style="display:none">
                        <div class="info-table">
                            <div class="it-row"><span class="it-label">Judul</span><span class="it-val">{{ $book->judul }}</span></div>
                            <div class="it-row"><span class="it-label">Penulis</span><span class="it-val">{{ $book->penulis }}</span></div>
                            <div class="it-row"><span class="it-label">Penerbit</span><span class="it-val">{{ $book->penerbit ?? '-' }}</span></div>
                            <div class="it-row"><span class="it-label">Kategori</span><span class="it-val">{{ $book->category->nama_kategori ?? '-' }}</span></div>
                            <div class="it-row"><span class="it-label">ISBN</span><span class="it-val">{{ $book->isbn ?? '-' }}</span></div>
                            <div class="it-row"><span class="it-label">Stok Tersedia</span><span class="it-val {{ $book->stok > 0 ? 'text-green' : 'text-red' }}">{{ $book->stok }}</span></div>
                            <div class="it-row"><span class="it-label">Rating</span><span class="it-val">{{ $rating > 0 ? $rating.' / 5.0' : 'Belum ada ulasan' }}</span></div>
                        </div>
                    </div>

                    {{-- TAB: ULASAN --}}
                    <div class="db-tab-content" id="tab-ulasan" style="display:none">
                        @if($book->reviews->count() > 0)
                            <div class="ulasan-summary">
                                <div class="us-rating-big">{{ $rating }}</div>
                                <div>
                                    <div class="us-stars">
                                        @for($i=1;$i<=5;$i++)
                                            <span class="{{ $i <= $rating ? 'ds-on' : 'ds-off' }}">★</span>
                                        @endfor
                                    </div>
                                    <div class="us-count">{{ $book->reviews->count() }} ulasan</div>
                                </div>
                            </div>
                            <div class="ulasan-items">
                                @foreach($book->reviews->take(5) as $review)
                                <div class="ui-item">
                                    <div class="ui-avatar">{{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}</div>
                                    <div class="ui-content">
                                        <div class="ui-header">
                                            <strong>{{ $review->user->name ?? 'Pengguna' }}</strong>
                                            <span class="ui-date">{{ $review->created_at ? $review->created_at->format('d M Y') : '' }}</span>
                                        </div>
                                        <div class="ui-stars">
                                            @for($i=1;$i<=5;$i++)
                                                <span class="{{ $i <= $review->rating ? 'ds-on' : 'ds-off' }}" style="font-size:.9rem">★</span>
                                            @endfor
                                        </div>
                                        <div class="ui-komentar">{{ $review->komentar ?? '-' }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-small" style="padding:40px">Belum ada ulasan untuk buku ini.</div>
                        @endif
                    </div>
                </div>

                {{-- BUKU LAIN --}}
                @if($bukuLain->count() > 0)
                <div class="dr-card" style="margin-top:20px">
                    <div class="dr-header">
                        <h4 class="dr-title">Buku Serupa</h4>
                        <a href="{{ route('user.katalog', ['kategori' => $book->kategori_id]) }}" class="dr-more">Lihat semua →</a>
                    </div>
                    <div class="buku-lain-grid">
                        @foreach($bukuLain as $bl)
                        @php $bidx = $bl->id % 6; @endphp
                        <a href="{{ route('user.buku.detail', $bl->id) }}" class="bl-card">
                            <div class="bl-cover" style="background:{{ $bcolors[$bidx] }}">
                                @if($bl->gambar)
                                    <img src="{{ asset('storage/'.$bl->gambar) }}" alt="{{ $bl->judul }}">
                                @else
                                    <span style="font-size:1.2rem;font-weight:900;color:{{ $btcolors[$bidx] }};opacity:.4">
                                        {{ strtoupper(substr($bl->judul, 0, 2)) }}
                                    </span>
                                @endif
                            </div>
                            <div class="bl-info">
                                <div class="bl-title">{{ $bl->judul }}</div>
                                <div class="bl-author">{{ $bl->penulis }}</div>
                                <div class="bl-stok {{ $bl->stok > 0 ? 'text-green' : 'text-red' }}">
                                    {{ $bl->stok > 0 ? 'Tersedia' : 'Habis' }}
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- PANEL KANAN --}}
            <div class="detail-right">

                {{-- Status Ketersediaan --}}
                <div class="dr-card">
                    <h4 class="dr-title">Status Ketersediaan</h4>
                    @if($book->stok > 0)
                    <div class="dk-status dk-available">
                        <span class="dks-icon">✅</span>
                        <div>
                            <div class="dks-label">Tersedia</div>
                            <div class="dks-sub">Buku ini tersedia untuk dipinjam.</div>
                        </div>
                    </div>
                    @else
                    <div class="dk-status dk-unavailable">
                        <span class="dks-icon">❌</span>
                        <div>
                            <div class="dks-label">Tidak Tersedia</div>
                            <div class="dks-sub">Semua stok sedang dipinjam.</div>
                        </div>
                    </div>
                    @endif

                    @if($sudahPinjam)
                        <button class="dba-btn dba-btn-disabled" style="width:100%;margin-top:12px" disabled>
                            📋 Sedang Dipinjam
                        </button>
                    @elseif($book->stok > 0)
                        <button onclick="openModal()" class="dba-btn dba-btn-pinjam" style="width:100%;margin-top:12px">
                            📅 Pinjam Buku
                        </button>
                    @else
                        <button class="dba-btn dba-btn-disabled" style="width:100%;margin-top:12px" disabled>
                            Stok Habis
                        </button>
                    @endif

                    <form action="{{ route('user.buku.favorit', $book->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="dba-btn dba-btn-fav {{ $isFav ? 'dba-fav-active' : '' }}" style="width:100%;margin-top:8px">
                            {{ $isFav ? '❤️ Hapus dari Favorit' : '🤍 Tambahkan ke Favorit' }}
                        </button>
                    </form>

                    <button onclick="bagikan()" class="dba-btn dba-btn-share" style="width:100%;margin-top:8px">
                        🔗 Bagikan Buku
                    </button>
                </div>

                {{-- Informasi Detail --}}
                <div class="dr-card">
                    <h4 class="dr-title">Informasi Detail</h4>
                    <div class="info-detail-list">
                        <div class="idl-item">
                            <span class="idl-label">ISBN</span>
                            <span class="idl-val">{{ $book->isbn ?? '-' }}</span>
                        </div>
                        <div class="idl-item">
                            <span class="idl-label">Kategori</span>
                            <span class="idl-val">{{ $book->category->nama_kategori ?? '-' }}</span>
                        </div>
                        <div class="idl-item">
                            <span class="idl-label">Penerbit</span>
                            <span class="idl-val">{{ $book->penerbit ?? '-' }}</span>
                        </div>
                        <div class="idl-item">
                            <span class="idl-label">Stok</span>
                            <span class="idl-val {{ $book->stok > 0 ? 'text-green' : 'text-red' }}">
                                {{ $book->stok }} eksemplar
                            </span>
                        </div>
                        <div class="idl-item">
                            <span class="idl-label">Total Ulasan</span>
                            <span class="idl-val">{{ $book->reviews->count() }} ulasan</span>
                        </div>
                    </div>
                </div>

                {{-- Info Peminjaman --}}
                <div class="dr-card" style="background:#eff6ff;border-color:#bfdbfe">
                    <div style="display:flex;gap:10px;align-items:flex-start">
                        <span style="font-size:1.2rem">ℹ️</span>
                        <p style="font-size:.8rem;color:#1e40af;line-height:1.6">
                            Peminjaman maksimal <strong>7 hari</strong>. Pastikan mengembalikan buku tepat waktu untuk menghindari denda keterlambatan Rp 2.000/hari.
                        </p>
                    </div>
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

function showTab(name, btn) {
    document.querySelectorAll('.db-tab-content').forEach(t => t.style.display = 'none');
    document.querySelectorAll('.dbt-btn').forEach(b => b.classList.remove('dbt-active'));
    document.getElementById('tab-' + name).style.display = 'block';
    btn.classList.add('dbt-active');
}

function bagikan() {
    if (navigator.share) {
        navigator.share({ title: '{{ $book->judul }}', url: window.location.href });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Link berhasil disalin!');
    }
}

// Modal
function openModal() {
    document.getElementById('modalOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('show');
    document.body.style.overflow = '';
}

function pilihDurasi(hari, el) {
    document.querySelectorAll('.durasi-option').forEach(d => d.classList.remove('durasi-selected'));
    el.classList.add('durasi-selected');
    document.getElementById('durasiInput').value = hari;
}

// Buka modal otomatis jika ada error
@if($errors->any() || session('error'))
    openModal();
@endif

function closeSukses() {
    const modal = document.getElementById('modalSukses');
    if (modal) {
        modal.style.opacity = '0';
        modal.style.transition = 'opacity .3s';
        setTimeout(() => modal.remove(), 300);
    }
}
</script>
{{-- MODAL PINJAM BUKU --}}
<div class="modal-overlay" id="modalOverlay" onclick="closeModal()">
    <div class="modal-box" onclick="event.stopPropagation()">

        {{-- Header --}}
        <div class="modal-header">
            <h3>📅 Pinjam Buku</h3>
            <button onclick="closeModal()" class="modal-close">✕</button>
        </div>

        {{-- Info Buku --}}
        <div class="modal-book-info">
            @php $midx = $book->id % 6; @endphp
            <div class="mbi-cover" style="background:{{ $bcolors[$midx] }}">
                @if($book->gambar)
                    <img src="{{ asset('storage/'.$book->gambar) }}" alt="{{ $book->judul }}">
                @else
                    <span style="font-size:.8rem;font-weight:900;color:{{ $btcolors[$midx] }};opacity:.5">
                        {{ strtoupper(substr($book->judul,0,2)) }}
                    </span>
                @endif
            </div>
            <div>
                <div class="mbi-title">{{ $book->judul }}</div>
                <div class="mbi-author">{{ $book->penulis }}</div>
                <span class="mbi-cat">{{ $book->category->nama_kategori ?? '-' }}</span>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('user.buku.pinjam', $book->id) }}" method="POST">
            @csrf
            <input type="hidden" name="durasi" id="durasiInput" value="7">

            <div class="modal-section-title">Pilih Durasi Peminjaman</div>

            <div class="durasi-options">
                <label class="durasi-option durasi-selected" onclick="pilihDurasi(7, this)">
                    <div class="do-radio">
                        <div class="do-dot"></div>
                    </div>
                    <div class="do-info">
                        <div class="do-label">7 Hari</div>
                        <div class="do-return">Pengembalian: {{ now()->addDays(7)->format('d M Y') }}</div>
                    </div>
                </label>

                <label class="durasi-option" onclick="pilihDurasi(3, this)">
                    <div class="do-radio">
                        <div class="do-dot"></div>
                    </div>
                    <div class="do-info">
                        <div class="do-label">3 Hari</div>
                        <div class="do-return">Pengembalian: {{ now()->addDays(3)->format('d M Y') }}</div>
                    </div>
                </label>

                <label class="durasi-option" onclick="pilihDurasi(14, this)">
                    <div class="do-radio">
                        <div class="do-dot"></div>
                    </div>
                    <div class="do-info">
                        <div class="do-label">14 Hari</div>
                        <div class="do-return">Pengembalian: {{ now()->addDays(14)->format('d M Y') }}</div>
                    </div>
                </label>
            </div>

            <div class="modal-info-box">
                <span>ℹ️</span>
                <span>Pastikan kamu mengembalikan buku tepat waktu agar tidak terkena denda keterlambatan Rp 2.000/hari.</span>
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeModal()" class="modal-btn-batal">Batal</button>
                <button type="submit" class="modal-btn-konfirmasi">📋 Konfirmasi Peminjaman</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL SUKSES PEMINJAMAN --}}
@if(session('pinjam_sukses'))
@php $ps = session('pinjam_sukses'); @endphp
<div class="modal-overlay show" id="modalSukses">
    <div class="modal-box modal-sukses-box" onclick="event.stopPropagation()">

        {{-- Animasi Sukses --}}
        <button onclick="closeSukses()" class="modal-close" style="position:absolute;top:16px;right:16px">✕</button>

        <div class="sukses-anim">
            <div class="sukses-ring"></div>
            <div class="sukses-check">✓</div>
            <div class="sukses-particles">
                <span class="sp sp1">✦</span>
                <span class="sp sp2">✦</span>
                <span class="sp sp3">✦</span>
                <span class="sp sp4">✦</span>
            </div>
        </div>

        <h3 class="sukses-title">Peminjaman Berhasil!</h3>
        <p class="sukses-desc">Buku telah berhasil dipinjam. Jangan lupa untuk mengembalikannya sebelum tanggal jatuh tempo.</p>

        {{-- Info Peminjaman --}}
        <div class="sukses-info-card">
            @php
                $sidx = $book->id % 6;
                $sbcolors = ['#fef3c7','#dbeafe','#dcfce7','#fce7f3','#ede9fe','#ffedd5'];
                $sbtcolors = ['#92400e','#1e40af','#166534','#9d174d','#5b21b6','#9a3412'];
            @endphp
            <div class="sic-book">
                <div class="sic-cover" style="background:{{ $sbcolors[$sidx] }}">
                    @if($book->gambar)
                        <img src="{{ asset('storage/'.$book->gambar) }}" alt="{{ $book->judul }}">
                    @else
                        <span style="font-size:.7rem;font-weight:900;color:{{ $sbtcolors[$sidx] }};opacity:.5">
                            {{ strtoupper(substr($ps['judul'],0,2)) }}
                        </span>
                    @endif
                </div>
                <div>
                    <div class="sic-title">{{ $ps['judul'] }}</div>
                    <div class="sic-author">{{ $ps['penulis'] }}</div>
                    <span class="sic-cat">{{ $ps['kategori'] }}</span>
                </div>
            </div>

            <div class="sic-details">
                <div class="sic-row">
                    <span class="sic-icon">⏱️</span>
                    <span class="sic-label">Durasi Peminjaman</span>
                    <span class="sic-val">{{ $ps['durasi'] }} Hari</span>
                </div>
                <div class="sic-row">
                    <span class="sic-icon">📅</span>
                    <span class="sic-label">Tanggal Pinjam</span>
                    <span class="sic-val">{{ $ps['tanggal_pinjam'] }}</span>
                </div>
                <div class="sic-row">
                    <span class="sic-icon">🕐</span>
                    <span class="sic-label">Tanggal Kembali</span>
                    <span class="sic-val" style="color:#dc2626;font-weight:700">{{ $ps['tanggal_kembali'] }}</span>
                </div>
            </div>

            <div class="sic-note">
                <span>ℹ️</span>
                <span>Kamu dapat melihat buku yang dipinjam di menu
                    <a href="{{ route('user.riwayat') }}" style="color:#1d4ed8;font-weight:700">Riwayat Peminjaman</a>.
                </span>
            </div>
        </div>

        <div class="sukses-actions">
            <button onclick="closeSukses()" class="modal-btn-batal">Kembali ke Beranda</button>
            <a href="{{ route('user.riwayat') }}" class="modal-btn-konfirmasi" style="text-decoration:none;display:flex;align-items:center;justify-content:center;gap:6px">
                📋 Lihat Riwayat Peminjaman
            </a>
        </div>
    </div>
</div>
@endif
</body>
</html>