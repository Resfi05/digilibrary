<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - DigiLibrary</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'Plus Jakarta Sans',sans-serif; background:#f5f6fa; color:#1e293b; display:flex; min-height:100vh; }

        /* SIDEBAR */
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
        .adm-logout { margin-left:auto;background:none;border:none;color:#ef4444;cursor:pointer;font-size:.75rem;font-weight:600;padding:6px 10px;border-radius:6px;font-family:inherit;transition:background .2s; }
        .adm-logout:hover { background:#fee2e2; }

        /* MAIN */
        .adm-main { margin-left:260px;flex:1;display:flex;flex-direction:column;min-height:100vh; }

        /* TOPBAR — hanya title + search */
        .adm-topbar { background:white;border-bottom:1px solid #f1f5f9;padding:0 28px;height:64px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50;box-shadow:0 1px 4px rgba(0,0,0,.04); }
        .adm-topbar-left { display:flex;flex-direction:column;justify-content:center; }
        .adm-topbar-title { font-size:1.05rem;font-weight:700;color:#111827; }
        .adm-topbar-sub   { font-size:.78rem;color:#94a3b8;margin-top:1px; }
        .adm-topbar-right { display:flex;align-items:center; }
        .adm-topbar-search { display:flex;align-items:center;gap:8px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:8px 16px;width:320px;transition:all .25s; }
        .adm-topbar-search:focus-within { border-color:#1a56db;box-shadow:0 0 0 3px rgba(26,86,219,.08); }
        .adm-topbar-search input { border:none;outline:none;background:transparent;font-family:inherit;font-size:.875rem;width:100%;color:#1e293b; }

        /* CONTENT */
        .adm-content { padding:24px 28px;flex:1; }

        /* ALERTS */
        .adm-alert { padding:12px 16px;border-radius:10px;font-size:.875rem;margin-bottom:16px; }
        .adm-alert-success { background:#dcfce7;border:1px solid #86efac;color:#15803d; }
        .adm-alert-error   { background:#fee2e2;border:1px solid #fca5a5;color:#b91c1c; }
        .adm-alert-warning { background:#fef9c3;border:1px solid #fde68a;color:#a16207; }

        @media(max-width:900px) {
            .adm-sidebar { display:none; }
            .adm-main { margin-left:0; }
            .adm-topbar-search { width:200px; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="adm-sidebar">
    <div class="adm-brand">
        <div class="adm-brand-icon">📚</div>
        <div>
            <div class="adm-brand-name">DigiLibrary</div>
            <div class="adm-brand-sub">Admin Panel</div>
        </div>
    </div>

    <nav class="adm-nav">
        <div class="adm-nav-label">Menu Utama</div>
        <a href="{{ route('admin.dashboard') }}"
            class="adm-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="adm-nav-icon">🏠</span> Dashboard
        </a>

        <div class="adm-nav-label">Manajemen</div>

        <div class="adm-nav-group {{ request()->routeIs('admin.user*') || request()->routeIs('admin.petugas.list*') ? 'open' : '' }}" id="groupPengguna">
            <div class="adm-nav-item {{ request()->routeIs('admin.user*') || request()->routeIs('admin.petugas.list*') ? 'active' : '' }}"
                onclick="toggleGroup('groupPengguna')">
                <span class="adm-nav-icon">👥</span> Manajemen Pengguna
                <span class="adm-nav-arrow">›</span>
            </div>
            <div class="adm-nav-children">
                <a href="{{ route('admin.user.index') }}"
                    class="adm-nav-item sub {{ request()->routeIs('admin.user.index') ? 'active' : '' }}">
                    Daftar User
                </a>
                <a href="{{ route('admin.petugas.list') }}"
                    class="adm-nav-item sub {{ request()->routeIs('admin.petugas.list') ? 'active' : '' }}">
                    Daftar Petugas
                </a>
            </div>
        </div>

        <div class="adm-nav-group {{ request()->routeIs('admin.buku*') ? 'open' : '' }}" id="groupBuku">
            <div class="adm-nav-item {{ request()->routeIs('admin.buku*') ? 'active' : '' }}"
                onclick="toggleGroup('groupBuku')">
                <span class="adm-nav-icon">📖</span> Manajemen Buku
                <span class="adm-nav-arrow">›</span>
            </div>
            <div class="adm-nav-children">
                <a href="{{ route('admin.buku.index') }}"
                    class="adm-nav-item sub {{ request()->routeIs('admin.buku.index') ? 'active' : '' }}">
                    Daftar Buku
                </a>
                <a href="{{ route('admin.buku.create') }}"
                    class="adm-nav-item sub {{ request()->routeIs('admin.buku.create') ? 'active' : '' }}">
                    Tambah Buku
                </a>
            </div>
        </div>

        <div class="adm-nav-group {{ request()->routeIs('admin.kategori*') ? 'open' : '' }}" id="groupKategori">
            <div class="adm-nav-item {{ request()->routeIs('admin.kategori*') ? 'active' : '' }}"
                onclick="toggleGroup('groupKategori')">
                <span class="adm-nav-icon">📂</span> Manajemen Kategori
                <span class="adm-nav-arrow">›</span>
            </div>
            <div class="adm-nav-children">
                <a href="{{ route('admin.kategori.index') }}"
                    class="adm-nav-item sub {{ request()->routeIs('admin.kategori.index') ? 'active' : '' }}">
                    Daftar Kategori
                </a>
            </div>
        </div>

        <div class="adm-nav-group {{ request()->routeIs('admin.peminjaman*') ? 'open' : '' }}" id="groupPeminjaman">
            <div class="adm-nav-item {{ request()->routeIs('admin.peminjaman*') ? 'active' : '' }}"
                onclick="toggleGroup('groupPeminjaman')">
                <span class="adm-nav-icon">🔄</span> Manajemen Peminjaman
                <span class="adm-nav-arrow">›</span>
            </div>
            <div class="adm-nav-children">
                <a href="{{ route('admin.peminjaman.index') }}"
                    class="adm-nav-item sub {{ request()->routeIs('admin.peminjaman.index') ? 'active' : '' }}">
                    Semua Peminjaman
                </a>
            </div>
        </div>

        <a href="{{ route('admin.denda.index') }}"
            class="adm-nav-item {{ request()->routeIs('admin.denda*') ? 'active' : '' }}">
            <span class="adm-nav-icon">💰</span> Kelola Denda
        </a>

        <a href="{{ route('admin.review.index') }}"
            class="adm-nav-item {{ request()->routeIs('admin.review*') ? 'active' : '' }}">
            <span class="adm-nav-icon">💬</span> Kelola Ulasan
        </a>

        <a href="{{ route('admin.notifikasi.index') }}"
            class="adm-nav-item {{ request()->routeIs('admin.notifikasi*') ? 'active' : '' }}">
            <span class="adm-nav-icon">🔔</span> Notifikasi
            @php $unread = \App\Models\Notification::where('is_read', false)->count(); @endphp
            @if($unread > 0)
            <span style="margin-left:auto;background:#ef4444;color:white;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:99px">
                {{ $unread > 99 ? '99+' : $unread }}
            </span>
            @endif
        </a>

        <a href="{{ route('admin.laporan.index') }}"
            class="adm-nav-item {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
            <span class="adm-nav-icon">📊</span> Laporan
        </a>
    </nav>

    <div class="adm-user-info">
        <div class="adm-user-avatar">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="">
            @else
                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
            @endif
        </div>
        <div>
            <div class="adm-user-name">{{ auth()->user()->name }}</div>
            <div class="adm-user-role">Administrator</div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="adm-logout">Keluar</button>
        </form>
    </div>
</aside>

{{-- MAIN --}}
<div class="adm-main">

    {{-- TOPBAR: hanya title + search --}}
    <header class="adm-topbar">
        <div class="adm-topbar-left">
            <div class="adm-topbar-title">@yield('page-title', 'Dashboard')</div>
            <div class="adm-topbar-sub">@yield('page-sub', '')</div>
        </div>
        <div class="adm-topbar-right">
            <div class="adm-topbar-search">
                <span style="color:#94a3b8">🔍</span>
                <input type="text" placeholder="Cari...">
            </div>
        </div>
    </header>

    <div class="adm-content">
        @if(session('success'))
        <div class="adm-alert adm-alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="adm-alert adm-alert-error">✕ {{ session('error') }}</div>
        @endif
        @if(session('warning'))
        <div class="adm-alert adm-alert-warning">⚠ {{ session('warning') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<script>
function toggleGroup(id) {
    const group = document.getElementById(id);
    const isOpen = group.classList.contains('open');
    document.querySelectorAll('.adm-nav-group').forEach(g => g.classList.remove('open'));
    if (!isOpen) group.classList.add('open');
}
</script>
@stack('scripts')
</body>
</html>