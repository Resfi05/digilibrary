<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DIGILIBRARY') | DIGILIBRARY</title>
    <link rel="stylesheet" href="{{ asset('css/petugas.css') }}">
</head>
<body>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">📚</div>
            <div class="logo-text">DIGI<span>LIBRARY</span></div>
        </div>

                <nav class="sidebar-nav">
            <div class="nav-label">Menu</div>
            <a href="{{ route('petugas.dashboard') }}" class="nav-item {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span>
                <span class="nav-text">Beranda</span>
            </a>

            <div class="nav-label">Transaksi</div>
            <a href="{{ route('petugas.peminjaman.validasi') }}" class="nav-item {{ request()->routeIs('petugas.peminjaman.validasi') ? 'active' : '' }}">
                <span class="nav-icon">📥</span>
                <span class="nav-text">Validasi Peminjaman</span>
                @php $pendingCount = \App\Models\Peminjaman::where('status', 'pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="nav-badge">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('petugas.peminjaman.pengembalian') }}" class="nav-item {{ request()->routeIs('petugas.peminjaman.pengembalian') ? 'active' : '' }}">
                <span class="nav-icon">📤</span>
                <span class="nav-text">Validasi Pengembalian</span>
            </a>

                        <div class="nav-label">Master Data</div>
            <a href="{{ route('petugas.buku.index') }}" class="nav-item {{ request()->routeIs('petugas.buku.*') ? 'active' : '' }}">
                <span class="nav-icon">📖</span>
                <span class="nav-text">Kelola Data Buku</span>
            </a>
            <a href="{{ route('petugas.kategori.index') }}" class="nav-item {{ request()->routeIs('petugas.kategori.*') ? 'active' : '' }}">
                <span class="nav-icon">📁</span>
                <span class="nav-text">Kelola Kategori</span>
            </a>
            <a href="{{ route('petugas.user.index') }}" class="nav-item {{ request()->routeIs('petugas.user.*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span>
                <span class="nav-text">Data Pengguna</span>
            </a>

            <div class="nav-label">Keuangan</div>
            <a href="{{ route('petugas.denda.index') }}" class="nav-item {{ request()->routeIs('petugas.denda.*') ? 'active' : '' }}">
                <span class="nav-icon">💰</span>
                <span class="nav-text">Kelola Denda</span>
                @php $dendaCount = \App\Models\Peminjaman::where('status', 'dikembalikan')->whereRaw('denda > 0')->where('bayar_denda', false)->count(); @endphp
                @if($dendaCount > 0)
                    <span class="nav-badge" style="background:#f59e0b;">{{ $dendaCount }}</span>
                @endif
            </a>

            <div class="nav-label">Lainnya</div>
            <a href="{{ route('petugas.laporan.index') }}" class="nav-item {{ request()->routeIs('petugas.laporan.*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span>
                <span class="nav-text">Laporan</span>
            </a>
            <a href="{{ route('petugas.notifikasi.index') }}" class="nav-item {{ request()->routeIs('petugas.notifikasi.*') ? 'active' : '' }}">
                <span class="nav-icon">🔔</span>
                <span class="nav-text">Notifikasi</span>
                @php $notifCount = \App\Models\Peminjaman::where('status', 'pending')->count(); @endphp
                @if($notifCount > 0)
                    <span class="nav-badge">{{ $notifCount }}</span>
                @endif
            </a>

            <div style="flex:1;"></div>

            <a href="{{ route('petugas.profil.index') }}" class="nav-item {{ request()->routeIs('petugas.profil.*') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span>
                <span class="nav-text">Profile</span>
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="user-row">
                <div class="user-pic">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div>
                    <div class="user-nm">{{ Auth::user()->name }}</div>
                    <div class="user-rl">Petugas Perpustakaan</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-link">🚪 Keluar</button>
            </form>
        </div>
    </aside>

    <!-- ===== MAIN ===== -->
    <main class="main-content">
        <!-- Top Bar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="mobile-toggle" onclick="toggleSidebar()">☰</button>
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="topbar-right">
                <span class="topbar-date">{{ date('l, d F Y') }}</span>
            </div>
        </header>

        <!-- Page Wrapper: Content + Detail Panel -->
        <div class="page-wrapper">
            <!-- Main Content -->
            <div class="page-main">
                @if(session('success'))
                    <div class="alert alert-success">✅ {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">❌ {{ session('error') }}</div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning">⚠️ {{ session('warning') }}</div>
                @endif

                @yield('content')
            </div>

            <!-- Detail Panel (Right Side) -->
            <div class="page-detail" id="detailPanel">
                <div class="page-detail-inner">
                    @yield('detail-panel')
                </div>
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }

        function openDetail() {
            document.getElementById('detailPanel').classList.add('active');
        }

        function closeDetail() {
            document.getElementById('detailPanel').classList.remove('active');
            document.querySelectorAll('.row-active').forEach(r => r.classList.remove('row-active'));
        }

        function selectRow(el) {
            document.querySelectorAll('.row-active').forEach(r => r.classList.remove('row-active'));
            el.classList.add('row-active');
        }

        function confirmDelete(url) {
            if (confirm('Yakin ingin menghapus?')) window.location.href = url;
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth > 992) {
                document.getElementById('sidebar').classList.remove('active');
                document.getElementById('sidebarOverlay').classList.remove('active');
            }
        });
    </script>
</body>
</html>