<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - DigiLibrary</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'Plus Jakarta Sans',sans-serif; background:#f5f6fa; color:#1e293b; display:flex; min-height:100vh; }
        .adm-sidebar { width:260px;min-height:100vh;background:white;border-right:1px solid #f1f5f9;display:flex;flex-direction:column;position:fixed;top:0;left:0;z-index:100;box-shadow:2px 0 12px rgba(0,0,0,.04);overflow-y:auto; }
        .adm-brand { padding:20px 20px 16px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px;position:sticky;top:0;background:white;z-index:1; }
        .adm-brand-icon { width:38px;height:38px;background:linear-gradient(135deg,#1a56db,#0e9f6e);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem; }
        .adm-brand-name { font-size:.95rem;font-weight:800;color:#111827; }
        .adm-brand-sub { font-size:.7rem;color:#6b7280; }
        .adm-nav { flex:1;padding:12px 0; }
        .adm-nav-label { font-size:.65rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;padding:14px 20px 6px; }
        .adm-nav-item { display:flex;align-items:center;gap:10px;padding:10px 20px;color:#64748b;text-decoration:none;font-size:.875rem;font-weight:500;transition:all .2s;border-left:3px solid transparent;margin:1px 0;cursor:pointer; }
        .adm-nav-item:hover { background:#f8fafc;color:#1a56db; }
        .adm-nav-item.active { background:#eff6ff;color:#1a56db;border-left-color:#1a56db;font-weight:700; }
        .adm-nav-item.sub { padding-left:36px;font-size:.82rem; }
        .adm-nav-icon { font-size:1rem;width:20px;text-align:center;flex-shrink:0; }
        .adm-nav-arrow { margin-left:auto;font-size:.7rem;transition:transform .2s;color:#94a3b8; }
        .adm-nav-group .adm-nav-children { display:none;background:#f8fafc; }
        .adm-nav-group.open .adm-nav-children { display:block; }
        .adm-nav-group.open .adm-nav-arrow { transform:rotate(90deg); }
        .adm-user-info { padding:16px 20px;border-top:1px solid #f1f5f9;display:flex;align-items:center;gap:10px; }
        .adm-user-avatar { width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;display:flex;align-items:center;justify-content:center;font-size:.875rem;font-weight:700;flex-shrink:0;overflow:hidden; }
        .adm-user-avatar img { width:100%;height:100%;object-fit:cover; }
        .adm-user-name { font-size:.82rem;font-weight:700;color:#111827; }
        .adm-user-role { font-size:.7rem;color:#6b7280; }
        .adm-logout { margin-left:auto;background:none;border:none;color:#ef4444;cursor:pointer;font-size:.75rem;font-weight:600;padding:6px 10px;border-radius:6px;transition:background .2s;font-family:inherit; }
        .adm-logout:hover { background:#fee2e2; }
        .adm-main { margin-left:260px;flex:1;display:flex;flex-direction:column;min-height:100vh; }

        /* TOPBAR - tanpa profile dan pengaturan */
        .adm-topbar { background:white;border-bottom:1px solid #f1f5f9;padding:0 28px;height:64px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50;box-shadow:0 1px 4px rgba(0,0,0,.04); }
        .adm-topbar-left { display:flex;flex-direction:column;justify-content:center; }
        .adm-page-title { font-size:1.05rem;font-weight:700;color:#111827; }
        .adm-page-sub { font-size:.78rem;color:#94a3b8;margin-top:1px; }
        .adm-topbar-right { display:flex;align-items:center;gap:12px; }
        .adm-topbar-search { display:flex;align-items:center;gap:8px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:8px 16px;width:320px;transition:all .25s; }
        .adm-topbar-search:focus-within { border-color:#1a56db;box-shadow:0 0 0 3px rgba(26,86,219,.08); }
        .adm-topbar-search input { border:none;outline:none;background:transparent;font-family:inherit;font-size:.875rem;width:100%;color:#1e293b; }

        /* CONTENT */
        .adm-content { padding:24px 28px;flex:1; }

        /* WELCOME */
        .adm-welcome { background:linear-gradient(135deg,#1a56db 0%,#0e9f6e 100%);border-radius:16px;padding:24px 28px;color:white;margin-bottom:24px;position:relative;overflow:hidden; }
        .adm-welcome::before { content:'';position:absolute;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.06);top:-60px;right:-40px; }
        .adm-welcome::after { content:'';position:absolute;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.04);bottom:-40px;right:120px; }
        .adm-welcome-text { position:relative; }
        .adm-welcome-text h2 { font-size:1.2rem;font-weight:800;margin-bottom:4px;color:#ffd166; }
        .adm-welcome-text p { font-size:.875rem;opacity:.85; }
        .adm-welcome-date { position:absolute;top:24px;right:28px;background:rgba(255,255,255,.15);border-radius:10px;padding:8px 16px;font-size:.82rem;font-weight:600;color:white;backdrop-filter:blur(4px);display:flex;align-items:center;gap:8px; }

        /* STATS */
        .adm-stats { display:grid;grid-template-columns:repeat(6,1fr);gap:16px;margin-bottom:24px; }
        .adm-stat-card { background:white;border-radius:14px;padding:18px 16px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04);transition:all .25s; }
        .adm-stat-card:hover { transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,.08); }
        .adm-stat-icon { width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;margin-bottom:12px; }
        .adm-stat-num { font-size:1.5rem;font-weight:800;color:#111827;margin-bottom:3px;line-height:1; }
        .adm-stat-label { font-size:.75rem;color:#64748b;font-weight:500; }
        .adm-stat-change { font-size:.72rem;font-weight:600;margin-top:6px;display:flex;align-items:center;gap:3px; }
        .change-up { color:#22c55e; }
        .change-down { color:#ef4444; }
        .change-neu { color:#94a3b8; }

        /* GRID */
        .adm-row { display:grid;gap:20px;margin-bottom:20px; }
        .adm-row-2 { grid-template-columns:2fr 1fr; }
        .adm-row-3 { grid-template-columns:1fr 1fr 1fr; }

        /* CARD */
        .adm-card { background:white;border-radius:14px;padding:20px;border:1px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.04); }
        .adm-card-header { display:flex;align-items:center;justify-content:space-between;margin-bottom:16px; }
        .adm-card-title { font-size:.95rem;font-weight:700;color:#111827; }
        .adm-card-more { font-size:.8rem;color:#1a56db;text-decoration:none;font-weight:600;padding:5px 12px;border-radius:6px;border:1px solid #dbeafe;transition:all .2s; }
        .adm-card-more:hover { background:#eff6ff; }

        /* CHART */
        .adm-chart-wrap { position:relative;height:220px; }

        /* TABLE */
        .adm-table { width:100%;border-collapse:collapse;font-size:.82rem; }
        .adm-table th { text-align:left;padding:8px 12px;background:#f8fafc;color:#64748b;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;border-bottom:1px solid #f1f5f9; }
        .adm-table td { padding:12px 12px;border-bottom:1px solid #f8fafc;color:#374151;vertical-align:middle; }
        .adm-table tr:last-child td { border-bottom:none; }
        .adm-table tr:hover td { background:#fafbff; }

        /* STATUS */
        .status-badge { padding:4px 10px;border-radius:99px;font-size:.72rem;font-weight:700; }
        .s-pending   { background:#fef9c3;color:#a16207; }
        .s-dipinjam  { background:#dbeafe;color:#1d4ed8; }
        .s-kembali   { background:#dcfce7;color:#15803d; }
        .s-terlambat { background:#fee2e2;color:#b91c1c; }
        .s-ditolak   { background:#f3f4f6;color:#6b7280; }

        /* AKTIVITAS */
        .adm-akt-item { display:flex;align-items:flex-start;gap:12px;padding:10px 0;border-bottom:1px solid #f8fafc; }
        .adm-akt-item:last-child { border-bottom:none; }
        .adm-akt-icon { width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0; }
        .adm-akt-text { flex:1; }
        .adm-akt-msg { font-size:.82rem;color:#374151;line-height:1.4; }
        .adm-akt-time { font-size:.72rem;color:#94a3b8;margin-top:2px; }

        /* USER */
        .adm-user-item { display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid #f8fafc; }
        .adm-user-item:last-child { border-bottom:none; }
        .adm-user-item-avatar { width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#1a56db,#0e9f6e);color:white;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;flex-shrink:0;overflow:hidden; }
        .adm-user-item-avatar img { width:100%;height:100%;object-fit:cover;border-radius:50%; }
        .adm-user-item-name  { font-size:.82rem;font-weight:600;color:#111827; }
        .adm-user-item-email { font-size:.72rem;color:#94a3b8; }
        .adm-user-item-badge { margin-left:auto;font-size:.68rem;font-weight:700;padding:3px 8px;border-radius:99px;background:#dcfce7;color:#15803d; }

        /* BUKU */
        .adm-buku-item { display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid #f8fafc; }
        .adm-buku-item:last-child { border-bottom:none; }
        .adm-buku-rank { width:22px;height:22px;border-radius:6px;background:#f1f5f9;color:#64748b;font-size:.72rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
        .adm-buku-rank.top { background:#fef3c7;color:#d97706; }
        .adm-buku-cover { width:32px;height:40px;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:900;flex-shrink:0; }
        .adm-buku-title { font-size:.82rem;font-weight:600;color:#111827; }
        .adm-buku-count { font-size:.72rem;color:#94a3b8;margin-top:2px; }
        .adm-buku-pinjam { margin-left:auto;font-size:.72rem;font-weight:600;color:#1a56db; }

        /* DONUT */
        .donut-wrap { display:flex;align-items:center;gap:20px; }
        .donut-legend { flex:1; }
        .donut-legend-item { display:flex;align-items:center;gap:8px;margin-bottom:8px;font-size:.8rem; }
        .donut-dot { width:10px;height:10px;border-radius:50%;flex-shrink:0; }
        .donut-label { flex:1;color:#374151; }
        .donut-pct { font-weight:700;color:#111827; }

        /* BTN */
        .adm-btn { display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:.82rem;font-weight:600;cursor:pointer;font-family:inherit;transition:all .2s;text-decoration:none;border:none; }
        .adm-btn-outline { border:1.5px solid #e2e8f0;background:white;color:#374151; }
        .adm-btn-outline:hover { border-color:#1a56db;color:#1a56db; }

        @media(max-width:1200px) { .adm-stats{grid-template-columns:repeat(3,1fr);} }
        @media(max-width:900px) { .adm-sidebar{display:none;} .adm-main{margin-left:0;} .adm-row-2,.adm-row-3{grid-template-columns:1fr;} .adm-stats{grid-template-columns:repeat(2,1fr);} .adm-topbar-search{width:200px;} }
    </style>
</head>
<body>

{{-- SIDEBAR --}}
<aside class="adm-sidebar">
    <div class="adm-brand">
        <div class="adm-brand-icon">📚</div>
        <div><div class="adm-brand-name">DigiLibrary</div><div class="adm-brand-sub">Admin Panel</div></div>
    </div>
    <nav class="adm-nav">
        <div class="adm-nav-label">Menu Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="adm-nav-item active">
            <span class="adm-nav-icon">🏠</span> Dashboard
        </a>
        <div class="adm-nav-label">Manajemen</div>

        <div class="adm-nav-group" id="groupPengguna">
            <div class="adm-nav-item" onclick="toggleGroup('groupPengguna')">
                <span class="adm-nav-icon">👥</span> Manajemen Pengguna
                <span class="adm-nav-arrow">›</span>
            </div>
            <div class="adm-nav-children">
                <a href="{{ route('admin.user.index') }}" class="adm-nav-item sub">Daftar User</a>
                <a href="{{ route('admin.petugas.list') }}" class="adm-nav-item sub">Daftar Petugas</a>
            </div>
        </div>

        <div class="adm-nav-group" id="groupBuku">
            <div class="adm-nav-item" onclick="toggleGroup('groupBuku')">
                <span class="adm-nav-icon">📖</span> Manajemen Buku
                <span class="adm-nav-arrow">›</span>
            </div>
            <div class="adm-nav-children">
                <a href="{{ route('admin.buku.index') }}" class="adm-nav-item sub">Daftar Buku</a>
                <a href="{{ route('admin.buku.create') }}" class="adm-nav-item sub">Tambah Buku</a>
            </div>
        </div>

        <div class="adm-nav-group" id="groupKategori">
            <div class="adm-nav-item" onclick="toggleGroup('groupKategori')">
                <span class="adm-nav-icon">📂</span> Manajemen Kategori
                <span class="adm-nav-arrow">›</span>
            </div>
            <div class="adm-nav-children">
                <a href="{{ route('admin.kategori.index') }}" class="adm-nav-item sub">Daftar Kategori</a>
            </div>
        </div>

        <div class="adm-nav-group" id="groupPeminjaman">
            <div class="adm-nav-item" onclick="toggleGroup('groupPeminjaman')">
                <span class="adm-nav-icon">🔄</span> Manajemen Peminjaman
                <span class="adm-nav-arrow">›</span>
            </div>
            <div class="adm-nav-children">
                <a href="{{ route('admin.peminjaman.index') }}" class="adm-nav-item sub">Semua Peminjaman</a>
            </div>
        </div>

        <a href="{{ route('admin.denda.index') }}" class="adm-nav-item">
            <span class="adm-nav-icon">💰</span> Kelola Denda
        </a>
        <a href="{{ route('admin.review.index') }}" class="adm-nav-item">
            <span class="adm-nav-icon">💬</span> Kelola Ulasan
        </a>
        <a href="{{ route('admin.notifikasi.index') }}" class="adm-nav-item">
            <span class="adm-nav-icon">🔔</span> Notifikasi
            @php $unread = \App\Models\Notification::where('is_read', false)->count(); @endphp
            @if($unread > 0)
            <span style="margin-left:auto;background:#ef4444;color:white;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:99px">
                {{ $unread > 99 ? '99+' : $unread }}
            </span>
            @endif
        </a>
        <a href="{{ route('admin.laporan.index') }}" class="adm-nav-item">
            <span class="adm-nav-icon">📊</span> Laporan
        </a>
    </nav>
    <div class="adm-user-info">
        <div class="adm-user-avatar">
            @if($admin->avatar)<img src="{{ asset('storage/'.$admin->avatar) }}" alt="">
            @else {{ strtoupper(substr($admin->name,0,1)) }} @endif
        </div>
        <div>
            <div class="adm-user-name">{{ $admin->name }}</div>
            <div class="adm-user-role">Administrator</div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf<button type="submit" class="adm-logout">Keluar</button>
        </form>
    </div>
</aside>

{{-- MAIN --}}
<div class="adm-main">

    {{-- TOPBAR: hanya title + search --}}
    <header class="adm-topbar">
        <div class="adm-topbar-left">
            <div class="adm-page-title">Dashboard Utama</div>
            <div class="adm-page-sub">Selamat datang kembali, {{ $admin->name }}! Berikut ringkasan aktivitas sistem.</div>
        </div>
        <div class="adm-topbar-right">
            <div class="adm-topbar-search">
                <span style="color:#94a3b8">🔍</span>
                <input type="text" placeholder="Cari buku, pengguna, atau kategori...">
            </div>
        </div>
    </header>

    <div class="adm-content">

        {{-- WELCOME --}}
        <div class="adm-welcome">
            <div class="adm-welcome-text">
                <h2>Selamat datang kembali, {{ $admin->name }}! 👋</h2>
                <p>Panel administrator DigiLibrary — Kelola perpustakaan digital dengan mudah dan efisien.</p>
            </div>
            <div class="adm-welcome-date">📅 {{ now()->translatedFormat('l, d F Y') }}</div>
        </div>

        {{-- STATS --}}
        <div class="adm-stats">
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:#dbeafe">📖</div>
                <div class="adm-stat-num">{{ number_format($stats['total_buku']) }}</div>
                <div class="adm-stat-label">Total Buku</div>
                <div class="adm-stat-change change-up">↑ Koleksi perpustakaan</div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:#dcfce7">👥</div>
                <div class="adm-stat-num">{{ number_format($stats['total_user']) }}</div>
                <div class="adm-stat-label">Total Pengguna</div>
                <div class="adm-stat-change change-up">↑ Anggota aktif</div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:#fef3c7">🔄</div>
                <div class="adm-stat-num">{{ number_format($stats['peminjaman_aktif']) }}</div>
                <div class="adm-stat-label">Peminjaman Aktif</div>
                <div class="adm-stat-change change-neu">● Sedang berjalan</div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:#f3e8ff">↩️</div>
                <div class="adm-stat-num">{{ number_format($stats['pengembalian']) }}</div>
                <div class="adm-stat-label">Pengembalian</div>
                <div class="adm-stat-change change-up">↑ Total selesai</div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:#fee2e2">💰</div>
                <div class="adm-stat-num" style="font-size:1.1rem">Rp {{ number_format($stats['total_denda'],0,',','.') }}</div>
                <div class="adm-stat-label">Total Denda</div>
                <div class="adm-stat-change change-down">↑ Akumulasi denda</div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:#e0f2fe">👨‍💼</div>
                <div class="adm-stat-num">{{ number_format($stats['petugas_aktif']) }}</div>
                <div class="adm-stat-label">Petugas Aktif</div>
                <div class="adm-stat-change change-neu">● Tidak ada perubahan</div>
            </div>
        </div>

        {{-- GRAFIK 30 HARI + AKTIVITAS --}}
        <div class="adm-row adm-row-2">
            <div class="adm-card">
                <div class="adm-card-header">
                    <div class="adm-card-title">📈 Grafik Peminjaman 30 Hari Terakhir</div>
                </div>
                <div class="adm-chart-wrap">
                    <canvas id="grafikChart"></canvas>
                </div>
                <div style="display:flex;gap:20px;margin-top:12px;justify-content:center">
                    <div style="display:flex;align-items:center;gap:6px;font-size:.78rem;color:#374151">
                        <div style="width:12px;height:12px;border-radius:3px;background:#1a56db"></div> Peminjaman
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;font-size:.78rem;color:#374151">
                        <div style="width:12px;height:12px;border-radius:3px;background:#0e9f6e"></div> Pengembalian
                    </div>
                </div>
            </div>

            <div class="adm-card">
                <div class="adm-card-header">
                    <div class="adm-card-title">⚡ Aktivitas Terbaru</div>
                    <a href="{{ route('admin.peminjaman.index') }}" class="adm-card-more">Lihat Semua</a>
                </div>
                @forelse($aktivitas as $ak)
                @php
                    $icon = match($ak->status) {
                        'dipinjam'     => ['📖','#dbeafe'],
                        'dikembalikan' => ['↩️','#dcfce7'],
                        'terlambat'    => ['⚠️','#fee2e2'],
                        'pending'      => ['⏳','#fef3c7'],
                        default        => ['📋','#f3f4f6'],
                    };
                @endphp
                <div class="adm-akt-item">
                    <div class="adm-akt-icon" style="background:{{ $icon[1] }}">{{ $icon[0] }}</div>
                    <div class="adm-akt-text">
                        <div class="adm-akt-msg">
                            <strong>{{ $ak->user->name ?? '-' }}</strong>
                            @if($ak->status=='dipinjam') meminjam
                            @elseif($ak->status=='dikembalikan') mengembalikan
                            @elseif($ak->status=='pending') mengajukan pinjam
                            @else terlambat mengembalikan
                            @endif
                            buku "<strong>{{ Str::limit($ak->book->judul ?? '-', 20) }}</strong>"
                        </div>
                        <div class="adm-akt-time">{{ $ak->updated_at ? $ak->updated_at->diffForHumans() : '-' }}</div>
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:30px;color:#94a3b8;font-size:.875rem">Belum ada aktivitas</div>
                @endforelse
            </div>
        </div>

        {{-- BUKU POPULER + USER BARU + KATEGORI --}}
        <div class="adm-row adm-row-3">
            <div class="adm-card">
                <div class="adm-card-header">
                    <div class="adm-card-title">🏆 Buku Terpopuler</div>
                    <a href="{{ route('admin.buku.index') }}" class="adm-card-more">Lihat Semua</a>
                </div>
                @php
                    $bcolors  = ['#fef3c7','#dbeafe','#dcfce7','#fce7f3','#ede9fe'];
                    $btcolors = ['#92400e','#1e40af','#166534','#9d174d','#5b21b6'];
                @endphp
                @forelse($bukuPopuler as $i => $buku)
                <div class="adm-buku-item">
                    <div class="adm-buku-rank {{ $i < 3 ? 'top' : '' }}">{{ $i+1 }}</div>
                    <div class="adm-buku-cover" style="background:{{ $bcolors[$i%5] }};color:{{ $btcolors[$i%5] }}">
                        {{ strtoupper(substr($buku->judul,0,2)) }}
                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="adm-buku-title">{{ Str::limit($buku->judul,22) }}</div>
                        <div class="adm-buku-count">{{ $buku->category->nama_kategori ?? '-' }}</div>
                    </div>
                    <div class="adm-buku-pinjam">{{ $buku->peminjaman_count }}x</div>
                </div>
                @empty
                <div style="text-align:center;padding:30px;color:#94a3b8;font-size:.875rem">Belum ada data</div>
                @endforelse
            </div>

            <div class="adm-card">
                <div class="adm-card-header">
                    <div class="adm-card-title">👤 Pengguna Baru</div>
                    <a href="{{ route('admin.user.index') }}" class="adm-card-more">Lihat Semua</a>
                </div>
                @forelse($userBaru as $u)
                <div class="adm-user-item">
                    <div class="adm-user-item-avatar">
                        @if($u->avatar)
                            <img src="{{ asset('storage/'.$u->avatar) }}" alt="">
                        @else
                            {{ strtoupper(substr($u->name,0,1)) }}
                        @endif
                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="adm-user-item-name">{{ $u->name }}</div>
                        <div class="adm-user-item-email">{{ $u->email }}</div>
                    </div>
                    <div>
                        <div class="adm-user-item-badge">Anggota</div>
                        <div style="font-size:.7rem;color:#94a3b8;text-align:right;margin-top:3px">
                            {{ $u->created_at ? $u->created_at->diffForHumans() : '-' }}
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:30px;color:#94a3b8;font-size:.875rem">Belum ada pengguna</div>
                @endforelse
            </div>

            <div class="adm-card">
                <div class="adm-card-header">
                    <div class="adm-card-title">📊 Peminjaman per Kategori</div>
                </div>
                @php
                    $katColors  = ['#1a56db','#0e9f6e','#f59e0b','#ef4444','#8b5cf6'];
                    $totalPinjam = $byKategori->sum('total_pinjam') ?: 1;
                @endphp
                <div class="donut-wrap">
                    <div style="flex-shrink:0">
                        <canvas id="donutChart" width="120" height="120"></canvas>
                    </div>
                    <div class="donut-legend">
                        @foreach($byKategori as $i => $kat)
                        <div class="donut-legend-item">
                            <div class="donut-dot" style="background:{{ $katColors[$i%5] }}"></div>
                            <span class="donut-label">{{ $kat->nama_kategori }}</span>
                            <span class="donut-pct">{{ round($kat->total_pinjam/$totalPinjam*100) }}%</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div style="text-align:center;margin-top:12px">
                    <div style="font-size:1.4rem;font-weight:800;color:#111827">{{ $byKategori->sum('total_pinjam') }}</div>
                    <div style="font-size:.78rem;color:#94a3b8">Total Peminjaman</div>
                </div>
            </div>
        </div>

        {{-- TABEL PEMINJAMAN TERBARU --}}
        <div class="adm-card">
            <div class="adm-card-header">
                <div class="adm-card-title">🔄 Peminjaman Terbaru</div>
                <div style="display:flex;gap:8px;align-items:center">
                    @if($stats['menunggu'] > 0)
                    <span style="background:#fef9c3;color:#a16207;padding:5px 12px;border-radius:99px;font-size:.78rem;font-weight:700">
                        ⏳ {{ $stats['menunggu'] }} menunggu konfirmasi
                    </span>
                    @endif
                    <a href="{{ route('admin.peminjaman.index') }}" class="adm-card-more">Lihat Semua →</a>
                </div>
            </div>
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aktivitas as $i => $p)
                    <tr>
                        <td style="color:#94a3b8">{{ $i+1 }}</td>
                        <td>
                            <div style="font-weight:600;color:#111827">{{ $p->user->name ?? '-' }}</div>
                            <div style="font-size:.72rem;color:#94a3b8">{{ $p->user->email ?? '-' }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600">{{ Str::limit($p->book->judul ?? '-', 25) }}</div>
                            <div style="font-size:.72rem;color:#94a3b8">{{ $p->book->category->nama_kategori ?? '-' }}</div>
                        </td>
                        <td>{{ $p->tanggal_pinjam ? $p->tanggal_pinjam->format('d M Y') : '-' }}</td>
                        <td>{{ $p->tanggal_kembali ? $p->tanggal_kembali->format('d M Y') : '-' }}</td>
                        <td>
                            <span class="status-badge
                                @if($p->status=='pending') s-pending
                                @elseif($p->status=='dipinjam') s-dipinjam
                                @elseif($p->status=='dikembalikan') s-kembali
                                @elseif($p->status=='terlambat') s-terlambat
                                @else s-ditolak @endif">
                                @if($p->status=='pending') Menunggu
                                @elseif($p->status=='dipinjam') Dipinjam
                                @elseif($p->status=='dikembalikan') Dikembalikan
                                @elseif($p->status=='terlambat') Terlambat
                                @else Ditolak @endif
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.peminjaman.index') }}" class="adm-btn adm-btn-outline" style="padding:5px 10px;font-size:.72rem">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:30px;color:#94a3b8">Belum ada peminjaman</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
// Grafik 30 hari — ambil dari controller
const grafikData = @json($grafik);
new Chart(document.getElementById('grafikChart'), {
    type: 'line',
    data: {
        labels: grafikData.map(d => d.label),
        datasets: [
            {
                label: 'Peminjaman',
                data: grafikData.map(d => d.peminjaman),
                borderColor: '#1a56db',
                backgroundColor: 'rgba(26,86,219,.08)',
                fill: true, tension: 0.4,
                pointBackgroundColor: '#1a56db', pointRadius: 3,
            },
            {
                label: 'Pengembalian',
                data: grafikData.map(d => d.pengembalian),
                borderColor: '#0e9f6e',
                backgroundColor: 'rgba(14,159,110,.08)',
                fill: true, tension: 0.4,
                pointBackgroundColor: '#0e9f6e', pointRadius: 3,
            }
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font:{size:10}, color:'#94a3b8' } },
            x: { grid: { display: false }, ticks: { font:{size:9}, color:'#94a3b8', maxRotation: 45 } }
        }
    }
});

// Donut
const katData = @json($byKategori);
const katColors = ['#1a56db','#0e9f6e','#f59e0b','#ef4444','#8b5cf6'];
new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels: katData.map(k => k.nama_kategori),
        datasets: [{ data: katData.map(k => k.total_pinjam || 0), backgroundColor: katColors, borderWidth: 2, borderColor: '#fff' }]
    },
    options: { responsive: false, plugins: { legend: { display: false } }, cutout: '70%' }
});

function toggleGroup(id) {
    const group = document.getElementById(id);
    const isOpen = group.classList.contains('open');
    document.querySelectorAll('.adm-nav-group').forEach(g => g.classList.remove('open'));
    if (!isOpen) group.classList.add('open');
}
</script>
</body>
</html>