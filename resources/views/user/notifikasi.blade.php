<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - DigiLibrary</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        {!! file_get_contents(resource_path('css/app.css')) !!}
        {!! file_get_contents(resource_path('css/dashboard.css')) !!}
        {!! file_get_contents(resource_path('css/notifikasi.css')) !!}
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
        <a href="{{ route('user.favorit') }}" class="sn-item"><span class="sn-icon">❤️</span><span>Buku Favorit</span></a>
        <a href="{{ route('user.ulasan') }}" class="sn-item"><span class="sn-icon">💬</span><span>Ulasan Saya</span></a>
        <a href="{{ route('user.notifikasi') }}" class="sn-item active">
            <span class="sn-icon">🔔</span><span>Notifikasi</span>
            @if($unreadNotif > 0)<span class="sn-badge">{{ $unreadNotif }}</span>@endif
        </a>
    </nav>
    <div class="sidebar-cta">
        <div class="scta-text"><strong>Baca lebih banyak,<br>wawasan lebih luas!</strong><br><small style="opacity:.7;font-size:.72rem">Temukan ribuan buku menarik untuk menemani harimu.</small></div>
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
        <div class="notif-layout">

            {{-- KONTEN UTAMA --}}
            <div class="notif-main">
                <div class="notif-header">
                    <div>
                        <h2>🔔 Notifikasi</h2>
                        <p>Informasi terbaru terkait aktivitas peminjaman, pengembalian, dan lainnya.</p>
                    </div>
                    @if($unreadNotif > 0)
                    <form action="{{ route('user.notifikasi.baca') }}" method="POST">
                        @csrf
                        <button type="submit" class="notif-baca-btn">✓ Tandai semua sebagai dibaca</button>
                    </form>
                    @endif
                </div>

                @if(session('success'))
                <div style="background:#dcfce7;border:1px solid #86efac;color:#15803d;padding:10px 16px;border-radius:10px;font-size:.875rem;margin-bottom:16px">
                    ✓ {{ session('success') }}
                </div>
                @endif

                {{-- TABS --}}
                <div class="notif-tabs">
                    @php
                    $tabs = [
                        'semua'      => ['label'=>'Semua', 'count'=>$notifications->total()],
                        'pinjam'     => ['label'=>'Peminjaman', 'count'=>$ringkasan['peminjaman']],
                        'kembali'    => ['label'=>'Pengembalian', 'count'=>$ringkasan['pengembalian']],
                        'ingat'      => ['label'=>'Pengingat', 'count'=>$ringkasan['pengingat']],
                        'ulasan'     => ['label'=>'Ulasan', 'count'=>$ringkasan['ulasan']],
                        'terlambat'  => ['label'=>'Peringatan', 'count'=>$ringkasan['peringatan']],
                    ];
                    @endphp
                    @foreach($tabs as $key => $tab)
                    <a href="{{ route('user.notifikasi', ['tipe'=>$key]) }}"
                       class="nt-tab {{ $tipe === $key ? 'nt-active' : '' }}">
                        {{ $tab['label'] }}
                        @if($tab['count'] > 0)
                        <span class="nt-count">{{ $tab['count'] }}</span>
                        @endif
                    </a>
                    @endforeach
                </div>

                {{-- LIST NOTIFIKASI --}}
                @if($notifications->count() > 0)
                @php
                   $grouped = $notifications->groupBy(function($n) {
                    if (!$n->created_at) return 'Lainnya';
                    $date = \Carbon\Carbon::parse($n->created_at);
                    if ($date->isToday()) return 'Hari Ini';
                    if ($date->isYesterday()) return 'Kemarin';
                    return $date->format('d M Y');
                });
                @endphp

                @foreach($grouped as $group => $items)
                <div class="notif-group">
                    <div class="ng-label">{{ $group }}</div>
                    <div class="notif-list">
                        @foreach($items as $notif)
                        @php
                            // Deteksi tipe notifikasi
                            $msg = strtolower($notif->pesan);
                            if (str_contains($msg, 'pinjam') || str_contains($msg, 'disetujui') || str_contains($msg, 'ditolak')) {
                                $icon = '📋'; $iconBg = '#dbeafe'; $tipeLabel = 'Peminjaman'; $tipeColor = '#1d4ed8'; $tipeBg = '#eff6ff';
                            } elseif (str_contains($msg, 'kembali') || str_contains($msg, 'dikembalikan')) {
                                $icon = '✅'; $iconBg = '#dcfce7'; $tipeLabel = 'Pengembalian'; $tipeColor = '#15803d'; $tipeBg = '#f0fdf4';
                            } elseif (str_contains($msg, 'terlambat') || str_contains($msg, 'denda')) {
                                $icon = '⚠️'; $iconBg = '#fee2e2'; $tipeLabel = 'Peringatan'; $tipeColor = '#b91c1c'; $tipeBg = '#fff1f2';
                            } elseif (str_contains($msg, 'ingat') || str_contains($msg, 'jatuh tempo')) {
                                $icon = '🔔'; $iconBg = '#fef9c3'; $tipeLabel = 'Pengingat'; $tipeColor = '#a16207'; $tipeBg = '#fefce8';
                            } elseif (str_contains($msg, 'ulasan')) {
                                $icon = '💬'; $iconBg = '#f3e8ff'; $tipeLabel = 'Ulasan'; $tipeColor = '#7c3aed'; $tipeBg = '#faf5ff';
                            } else {
                                $icon = 'ℹ️'; $iconBg = '#f3f4f6'; $tipeLabel = 'Sistem'; $tipeColor = '#374151'; $tipeBg = '#f9fafb';
                            }
                        @endphp
                        <div class="notif-item {{ !$notif->is_read ? 'notif-unread-item' : '' }}"
                             onclick="bacaNotif(this, {{ $notif->id }})">
                            {{-- Dot unread --}}
                            @if(!$notif->is_read)
                            <div class="ni-dot"></div>
                            @endif

                            {{-- Icon --}}
                            <div class="ni-icon" style="background:{{ $iconBg }}">{{ $icon }}</div>

                            {{-- Konten --}}
                            <div class="ni-content">
                                <div class="ni-msg">{{ $notif->pesan }}</div>
                                <div class="ni-meta">
                                    <span class="ni-tipe" style="color:{{ $tipeColor }};background:{{ $tipeBg }}">{{ $tipeLabel }}</span>
                                    <span class="ni-time">{{ $notif->created_at ? $notif->created_at->diffForHumans() : '' }}</span>
                                </div>
                            </div>

                            {{-- Arrow --}}
                            <div class="ni-arrow">›</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                {{-- PAGINATION --}}
                <div class="katalog-pagination" style="margin-top:24px">
                    @if($notifications->onFirstPage())
                        <span class="kp-btn kp-disabled">‹</span>
                    @else
                        <a href="{{ $notifications->previousPageUrl() }}" class="kp-btn">‹</a>
                    @endif
                    @foreach($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
                        @if($page == $notifications->currentPage())
                            <span class="kp-btn kp-active">{{ $page }}</span>
                        @elseif($page == 1 || $page == $notifications->lastPage() || abs($page - $notifications->currentPage()) <= 1)
                            <a href="{{ $url }}" class="kp-btn">{{ $page }}</a>
                        @elseif(abs($page - $notifications->currentPage()) == 2)
                            <span class="kp-dots">...</span>
                        @endif
                    @endforeach
                    @if($notifications->hasMorePages())
                        <a href="{{ $notifications->nextPageUrl() }}" class="kp-btn">›</a>
                    @else
                        <span class="kp-btn kp-disabled">›</span>
                    @endif
                </div>

                @else
                <div class="katalog-empty" style="padding:80px 20px">
                    <div class="ke-emoji">🔕</div>
                    <h3>Tidak ada notifikasi</h3>
                    <p>Semua notifikasi sudah dibaca atau belum ada aktivitas.</p>
                </div>
                @endif
            </div>

            {{-- PANEL KANAN --}}
            <div class="notif-right">

                {{-- Ringkasan --}}
                <div class="dr-card">
                    <h4 class="dr-title">Ringkasan Notifikasi</h4>
                    <div class="notif-summary">
                        <div class="ns-item">
                            <div class="ns-icon" style="background:#dbeafe">💬</div>
                            <div class="ns-label">Peminjaman</div>
                            <span class="ns-val" style="color:#1d4ed8">{{ $ringkasan['peminjaman'] }}</span>
                        </div>
                        <div class="ns-item">
                            <div class="ns-icon" style="background:#dcfce7">✅</div>
                            <div class="ns-label">Pengembalian</div>
                            <span class="ns-val" style="color:#15803d">{{ $ringkasan['pengembalian'] }}</span>
                        </div>
                        <div class="ns-item">
                            <div class="ns-icon" style="background:#fef9c3">🔔</div>
                            <div class="ns-label">Pengingat</div>
                            <span class="ns-val" style="color:#a16207">{{ $ringkasan['pengingat'] }}</span>
                        </div>
                        <div class="ns-item">
                            <div class="ns-icon" style="background:#f3e8ff">💬</div>
                            <div class="ns-label">Ulasan</div>
                            <span class="ns-val" style="color:#7c3aed">{{ $ringkasan['ulasan'] }}</span>
                        </div>
                        <div class="ns-item">
                            <div class="ns-icon" style="background:#fee2e2">⚠️</div>
                            <div class="ns-label">Peringatan</div>
                            <span class="ns-val" style="color:#b91c1c">{{ $ringkasan['peringatan'] }}</span>
                        </div>
                        <div class="ns-divider"></div>
                        <div class="ns-item ns-total">
                            <div class="ns-label"><strong>Total</strong></div>
                            <span class="ns-val" style="color:#111827"><strong>{{ $notifications->total() }}</strong></span>
                        </div>
                    </div>
                </div>

                {{-- Pengaturan --}}
                <div class="dr-card">
                    <h4 class="dr-title">Pengaturan Notifikasi</h4>
                    <p style="font-size:.8rem;color:#6b7280;margin-bottom:14px">Atur jenis notifikasi yang ingin Anda terima.</p>
                    <div class="notif-settings">
                        <div class="nset-item">
                            <div class="nset-icon">✉️</div>
                            <div class="nset-label">Email</div>
                            <div class="nset-arrow">›</div>
                        </div>
                        <div class="nset-item">
                            <div class="nset-icon">🔔</div>
                            <div class="nset-label">Push Notification</div>
                            <div class="nset-arrow">›</div>
                        </div>
                        <div class="nset-item">
                            <div class="nset-icon">💬</div>
                            <div class="nset-label">SMS</div>
                            <div class="nset-arrow">›</div>
                        </div>
                    </div>
                    <a href="#" class="rr-stat-btn" style="margin-top:14px">⚙️ Kelola Pengaturan</a>
                </div>

                {{-- Bantuan --}}
                <div class="dr-card">
                    <h4 class="dr-title">Butuh Bantuan?</h4>
                    <p class="rr-help-text">Jika ada pertanyaan seputar notifikasi, hubungi petugas perpustakaan.</p>
                    <a href="#" class="rr-help-btn">🎧 Hubungi Petugas</a>
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

function bacaNotif(el, id) {
    el.classList.remove('notif-unread-item');
    const dot = el.querySelector('.ni-dot');
    if (dot) { dot.style.opacity = '0'; setTimeout(() => dot.remove(), 300); }
}
</script>
</body>
</html>